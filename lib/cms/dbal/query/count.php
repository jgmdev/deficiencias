<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

/**
 * SQL Abstraction layer for counting columns on a table.
 */
class Count extends \Cms\DBAL\Query
{
    public $table;
    public $column;
    public $as_column;
    
    public function __construct($table, $column, $as_column)
    {
        $this->table = $table;
        $this->column = $column;
        $this->as_column = $as_column;
        
        return $this;
    }
    
    protected function GetSQLiteSQL()
    {
        $sql = 'select ';
        
        $sql .= 'count('.$this->column.') as ' . $this->as_column;
        
        $sql .= ' from ' . $this->table;
        
        $sql .= $this->GetSQLiteOperations();
        
        return $sql;
    }
}
?>
