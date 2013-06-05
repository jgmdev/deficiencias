<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\DBAL\DataSource;
use Cms\Enumerations\FieldType;

class Insert
{
    public $table;
    public $fields;
    
    public function __construct($table)
    {
        $this->table = $table;
        $this->fields = array();
        
        return $this;
    }
    
    public function Insert($field, $value, $type)
    {
        $this->fields[$field] = array(
            'value'=>$value,
            'type'=>$type
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
        $sql = 'insert into ';
        $sql .= $this->table . ' ';
        $sql .= '(';
        
        foreach($this->fields as $name=>$options)
        { 
            $sql .= "$name, ";
        }
        $sql = trim($sql, ', ');
        
        $sql .= ') ';
        $sql .= 'values(';
        
        foreach($this->fields as $name=>$options)
        {
            switch($options['type'])
            {
                case FieldType::BOOLEAN:
                    $sql .= ($options["value"]?1:0) . ', ';
                    break;
                
                case FieldType::INTEGER:
                    $sql .= $sql .= intval($options["value"]) . ', ';
                    break;
                
                case FieldType::REAL:
                    $sql .= doubleval($options["value"]) . ', ';
                    break;
                
                case FieldType::TEXT:
                    $sql .= "'" . str_replace("'", "''", $options["value"])."', ";
                    break;
            }
        }
        $sql = rtrim($sql, ', ');
        
        $sql .= ')';
        
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
