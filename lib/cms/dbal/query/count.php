<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\Enumerations\FieldType;

class Count extends \Cms\DBAL\Query
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
    
    protected function GetSQLiteSQL()
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
}
?>
