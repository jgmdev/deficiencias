<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\Enumerations\Sort;

/**
 * SQL Abstraction layer for indexing a database table.
 */
class Index extends \Cms\DBAL\Query
{
    /**
     * Array of fields with the sorting mechanism.
     * @var array
     */
    public $fields;
    
    /**
     * Name of index.
     * @var string
     */
    public $name;
    
    /**
     * Name of the table this index is going to be created for.
     * @var string
     */
    public $table;
    
    /**
     * Default constructor.
     * @param string $name
     * @param string $table
     * @return \Cms\DBAL\Query\Index
     */
    public function __construct($name, $table)
    {
        $this->name = $name;
        $this->table = $table;
        $this->fields = array();
        
        return $this;
    }
    
    public function AddFieldAsc($name)
    {
        $this->fields[$name] = Sort::ASCENDING;
        return $this;
    }
    
    public function AddFieldDesc($name)
    {
        $this->fields[$name] = Sort::DESCENDING;
        return $this;
    }
    
    protected function GetSQLiteSQL()
    {
        $sql = 'create index ';
        $sql .= $this->name . ' ';
        $sql .= 'on ' . $this->table . ' ';
        
        $sql .= '(';
        
        foreach($this->fields as $name=>$sorting)
        {   
            switch($sorting)
            {
                case Sort::ASCENDING:
                    $sql .= "$name asc, ";
                    break;
                
                case Sort::DESCENDING:
                    $sql .= "$name desc, ";
                    break;
            }
        }
        
        $sql = rtrim($sql, ', ');
        
        $sql .= ')';
        
        return $sql;
    }
}
?>
