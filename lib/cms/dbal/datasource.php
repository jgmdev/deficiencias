<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

class DataSource
{
    const SQLITE = 'sqlite';
    const MYSQL = 'mysql';
    const POSTGRESQL = 'postgresql';
    
    private $dsn;
    public $username;
    public $password;
    public $type;
    
    public function GetDSN()
    {
        if($this->dsn)
            return $this->dsn;
        
        throw new Exception(t("Data source hasn't been initialized."));
    }
    
    public function InitAsSQLite($file, $path='')
    {
        if($path)
            $path = rtrim(str_replace('\\', '/', $path), '/') . "/";
        
        $this->type = self::SQLITE;
        
        $this->dsn = "sqlite:{$path}{$file}";
    }
    
    public function InitAsMySql($database, $username, $password, $host='127.0.0.1', $port='3306')
    {
        $this->type = self::MYSQL;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->dsn = "mysql:dbname=$database;host=$host;port=$port;";
    }
    
    public function InitAsPostgreSQL($database, $username, $password, $host='127.0.0.1', $port='5432')
    {
        $this->type = self::POSTGRESQL;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->dsn = "pgsql:dbname=$database;host=$host;port=$port;";
    }
}
?>
