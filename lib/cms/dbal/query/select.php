<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

/**
 * SQL Abstraction layer for querying data from a table.
 */
class Select extends \Cms\DBAL\Query
{
    public $table;
    public $columns;
    public $columns_custom;
    public $all;
    public $limit;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->columns = array();
        $this->columns_custom = array();
        $this->limit = array();
        
        return $this;
    }
    
    public function Select($column)
    {
        if(!$this->all)
            $this->columns[] = $column;
        else
            throw new \Exception(t('All columns already selected.'));
        
        return $this;
    }
    
    public function SelectAll()
    {
        if(count($this->columns) == 0)
            $this->all = true;
        else
            throw new \Exception(t('Select all not allowed if columns where previously selected.'));
        
        return $this;
    }
    
    public function SelectCustom($statement)
    {
        if(!$this->all)
            $this->columns_custom[] = $statement;
        else
            throw new \Exception(t('All columns already selected.'));
        
        return $this;
    }
    
    public function Limit($from, $to)
    {
        $this->limit = array(intval($from), intval($to));
        
        return $this;
    }
    
    protected function GetSQLiteSQL()
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
        
        $sql .= $this->GetSQLiteOperations();
        
        if(count($this->limit) > 0)
        {
            $sql .= ' limit ' . $this->limit[0] . ',' . $this->limit[1];
        }
        
        return $sql;
    }
}
?>
