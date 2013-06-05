<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL\Query;

use Cms\DBAL\DataSource;
use Cms\Enumerations\FieldType;

class Table
{
    public $fields;
    public $primary_keys;
    public $name;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->fields = array();
        $this->primary_keys = array();
        
        return $this;
    }
    
    public function AddIntegerField($name)
    {
        $this->fields[$name] = FieldType::INTEGER;
        return $this;
    }
    
    public function AddRealField($name)
    {
        $this->fields[$name] = FieldType::REAL;
        return $this;
    }
    
    public function AddBooleanField($name)
    {
        $this->fields[$name] = FieldType::BOOLEAN;
    }
    
    public function AddTextField($name)
    {
        $this->fields[$name] = FieldType::TEXT;
        return $this;
    }
    
    public function AddPrimaryKey($field_name)
    {
        if(isset($this->fields[$field_name]))
        {
            $this->primary_keys[] = $field_name;
        }
        else
        {
            throw new Exception(t('Field is not defined.'));
        }
        
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
        $sql = 'create table if not exists ';
        $sql .= $this->name . ' ';
        $sql .= '(';
        
        foreach($this->fields as $name=>$type)
        {   
            switch($type)
            {
                case FieldType::BOOLEAN:
                    $sql .= "$name bool, ";
                    break;
                
                case FieldType::INTEGER:
                    $sql .= "$name integer, ";
                    break;
                
                case FieldType::REAL:
                    $sql .= "$name real, ";
                    break;
                
                case FieldType::TEXT:
                    $sql .= "$name text, ";
                    break;
            }
        }
        
        if(count($this->primary_keys) > 0)
        {
            $sql .= "PRIMARY KEY (";
            $sql .= implode(',', $this->primary_keys);
            $sql .= ")";
        }
        else
        {
            $sql = rtrim($sql, ', ');
        }
        
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
