<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\DBAL\DataSource;
use Cms\Enumerations\FieldType;

class Update
{
    public $table;
    public $columns;
    public $increments;
    public $decrements;
    public $where;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->columns = array();
        $this->increments = array();
        $this->decrements = array();
        $this->where = array();
        
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
    }
    
    public function Decrement($column, $value=1)
    {
        $this->CheckColumnNotSet($column);
        
        $this->decrements[$column] = $value;
    }
    
    public function WhereEqual($column, $value, $type)
    {
        $this->where[] = array(
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'='
        );
        
        return $this;
    }
    
    public function WhereNotEqual($column, $value, $type)
    {
        $this->where[] = array(
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'!='
        );
        
        return $this;
    }
    
    public function WhereMoreThan($column, $value, $type)
    {
        $this->where[] = array(
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'>'
        );
        
        return $this;
    }
    
    public function WhereLessThan($column, $value, $type)
    {
        $this->where[] = array(
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'<'
        );
        
        return $this;
    }
    
    /**
     * Generates the sql code to create a table depending on database type.
     * @param string $type One of the constants from \Cms\DBAL\DataSource
     */
    public function GetSQL($type)
    {
        switch($type)
        {
            case DataSource::SQLITE;
                return $this->GetSQLiteSQL();
                
            case DataSource::MYSQL;
                return $this->GetMySqlSQL();
                
            case DataSource::POSTGRESQL;
                return $this->GetPostgreSQL();
        }
    }
    
    private function GetSQLiteSQL()
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
        
        if(count($this->where) > 0)
        {
            $sql .= ' where ';
            
            foreach($this->where as $where)
            {
                $sql .= $where["column"] . ' ' . $where['op'] . ' ';
                
                switch($where['type'])
                {
                    case FieldType::BOOLEAN:
                        $sql .= ($where["value"]?1:0) . ' and ';
                        break;

                    case FieldType::INTEGER:
                        $sql .= $sql .= intval($where["value"]) . ' and ';
                        break;

                    case FieldType::REAL:
                        $sql .= doubleval($where["value"]) . ' and ';
                        break;

                    case FieldType::TEXT:
                        $sql .= "'" . str_replace("'", "''", $where["value"])."' and ";
                        break;
                }
            }
            
            $sql = rtrim($sql, 'and ');
        }
        
        return $sql;
    }
    
    private function GetMySqlSQL()
    {
        throw new Exception('Not implemented');
    }
    
    private function GetPostgreSQL()
    {
        throw new Exception('Not implemented');
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
}
?>
