<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\Enumerations\FieldType;

/**
 * SQL Abstraction layer for updating a row data of a table.
 */
class Update extends \Cms\DBAL\Query
{
    public $table;
    public $columns;
    public $increments;
    public $decrements;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->columns = array();
        $this->increments = array();
        $this->decrements = array();
        
        return $this;
    }
    
    /**
     * Update a column on a table
     * @param string $column
     * @param string $value
     * @param \Cms\Enumerations\FieldType $type
     * @return \Cms\DBAL\Query\Update
     */
    public function Update($column, $value, $type)
    {
        $this->CheckColumnNotSet($column);
        
        $this->columns[$column] = array(
            'value'=>$value,
            'type'=>$type
        );
        
        return $this;
    }
    
    public function Increment($column, $value=1)
    {
        $this->CheckColumnNotSet($column);
        
        $this->increments[$column] = $value;
        
        return $this;
    }
    
    public function Decrement($column, $value=1)
    {
        $this->CheckColumnNotSet($column);
        
        $this->decrements[$column] = $value;
        
        return $this;
    }
    
    private function CheckColumnNotSet($column)
    {
        if(
            isset($this->columns[$column]) ||
            isset($this->increments[$column]) ||
            isset($this->decrements[$column])
        )
            throw new \Exception(t('The column was already assigned.'));
    }
    
    protected function GetSQLiteSQL()
    {
        $sql = 'update ';
        
        $sql .= $this->table . ' set ';
        
        if(count($this->columns) > 0)
        {
            foreach($this->columns as $name=>$data)
            {
                switch($data['type'])
                {
                    case FieldType::BOOLEAN:
                        $sql .= $name . '=' .($data["value"]?1:0) . ', ';
                        break;

                    case FieldType::INTEGER:
                        $sql .= $name . '=' . intval($data["value"]) . ', ';
                        break;

                    case FieldType::REAL:
                        $sql .= $name . '=' . doubleval($data["value"]) . ', ';
                        break;

                    case FieldType::TEXT:
                        $sql .= $name . '=' . "'" . str_replace("'", "''", $data["value"])."', ";
                        break;
                }
            }
        }
        
        if(count($this->increments) > 0)
        {
            foreach($this->increments as $name=>$value)
            {
                $sql .= "$name=$name+" . intval($value);
            }
        }
        
        if(count($this->decrements) > 0)
        {
            foreach($this->decrements as $name=>$value)
            {
                $sql .= "$name=$name-" . intval($value);
            }
        }
        
        $sql = rtrim($sql, ', ');
        
        $sql .= $this->GetSQLiteOperations();
        
        return $sql;
    }
}
?>
