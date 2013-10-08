<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

/**
 * SQL Abstraction layer for deleting a row from a table.
 */
class Delete extends \Cms\DBAL\Query
{
    public $table;
    
    public function __construct($table)
    {
        $this->table = $table;
        
        return $this;
    }
    
    protected function GetSQLiteSQL()
    {
        $sql = 'delete from ';
        
        $sql .= $this->table;
        
        $sql .= $this->GetSQLiteOperations();
        
        return $sql;
    }
}
?>
