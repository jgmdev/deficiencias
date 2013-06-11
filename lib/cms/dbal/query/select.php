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
    public $columns_custom;
    public $all;
    public $where;
    public $limit;
    public $order_by;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->columns = array();
        $this->columns_custom = array();
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
    
    public function SelectCustom($statement)
    {
        if(!$this->all)
            $this->columns_custom[] = $statement;
        else
            throw new \Exception(t("All columns already selected."));
        
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
    
    public function WhereCustom($statement)
    {
        $this->where[] = array(
            'value'=>$statement,
            'type'=>'custom',
        );
        
        return $this;
    }
    
    /**
     * 
     * @param string $column
     * @param integer $sort @see \Cms\Enumerations\Sort
     * @return \Cms\DBAL\Query\Select
     */
    public function OrderBy($column, $sort=\Cms\Enumerations\Sort::ASCENDING)
    {
        $this->order_by[] = array(
            'column'=>$column,
            'sort'=>$sort,
            'custom'=>false
        );
        
        return $this;
    }
    
    public function OrderByCustom($statement, $sort=\Cms\Enumerations\Sort::ASCENDING)
    {
        $this->order_by[] = array(
            'statement'=>$statement,
            'sort'=>$sort,
            'custom'=>true
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
        
        if(!$this->all && count($this->columns_custom) > 0)
        {   
            foreach($this->columns_custom as $statement)
            {
                $sql .= $statement . ', ';
            }
            
            $sql = rtrim($sql, ', ');
        }
        
        $sql .= ' from ' . $this->table;
        
        if(count($this->where) > 0)
        {
            $sql .= ' where ';
            
            foreach($this->where as $where)
            {
                if($where['type'] == 'custom')
                {
                    $sql .= $where["value"] . ' ';
                    continue;
                }
                
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
        
        if(count($this->order_by) > 0)
        {
            $sql .= ' order by ';
            
            foreach($this->order_by as $order)
            {
                if($order['custom'])
                {
                    $sql .= $order["statement"] . ' ';
                }
                else
                {
                    $sql .= $order["column"] . ' ';
                }
                
                if($order["sort"] == \Cms\Enumerations\Sort::ASCENDING)
                    $sql .= 'asc, ';
                else
                    $sql .= 'desc, ';
            }
            
            $sql = rtrim($sql, ', ');
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
