<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\DBAL\DataSource;
use Cms\Enumerations\FieldType;

class Count
{
    public $table;
    public $column;
    public $as_column;
    public $where;
    
    public function __construct($table, $column, $as_column)
    {
        $this->table = $table;
        $this->column = $column;
        $this->as_column = $as_column;
        $this->where = array();
        
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
        
        $sql .= 'count('.$this->column.') as ' . $this->as_column;
        
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
                        $sql .= intval($where["value"]) . ' and ';
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
}
?>
