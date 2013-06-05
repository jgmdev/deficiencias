<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\DBAL\DataSource;
use Cms\Enumerations\FieldType;

class Select
{
    public $table;
    public $columns;
    public $all;
    public $where;
    public $limit;
    public $order_by;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->columns = array();
        $this->where = array();
        $this->order_by = array();
        
        return $this;
    }
    
    public function Select($column)
    {
        if(!$this->all)
            $this->columns[] = $column;
        else
            throw new \Exception(t("All columns already selected."));
        
        return $this;
    }
    
    public function SelectAll()
    {
        if(count($this->columns) == 0)
            $this->all = true;
        else
            throw new \Exception(t("Select all not allowed if columns where previously selected."));
        
        return $this;
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
    
    public function Limit($from, $to)
    {
        $this->limit = array($from, $to);
        
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
        $sql = 'select ';
        
        if($this->all)
            $sql .= '*';
        else
            $sql .= implode(',', $this->columns);
        
        $sql .= ' from ' . $this->table;
        
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
        
        if(count($this->limit) > 0)
        {
            $sql .= ' limit ' . $this->limit[0] . ',' . $this->limit[1];
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
}
?>
