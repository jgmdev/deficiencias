<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

use Cms\Enumerations\DBDataSource;

/**
 * For building Data Source Names for PDO connections.
 */
class DataSource
{
    /**
     * @var string
     */
    private $dsn;
    
    /**
     * Username for MySQL and PostgreSQL
     * @var string
     */
    public $username;
    
    /**
     * Password for MySQL and PostgreSQL
     * @var string
     */
    public $password;
    
    /**
     * @see \Cms\Enumerations\DBDataSource
     * @var string
     */
    public $type;
    
    /**
     * Gets the generated dsn string from calling one of the Init* functions.
     * @return string
     * @throws Exception
     */
    public function GetDSN()
    {
        if($this->dsn)
            return $this->dsn;
        
        throw new Exception(t("Data source hasn't been initialized."));
    }
    
    /**
     * Generate SQLite dsn.
     * @param string $file
     * @param string $path
     */
    public function InitAsSQLite($file, $path='')
    {
        if($path)
            $path = rtrim(str_replace('\\', '/', $path), '/') . "/";
        
        $this->type = DBDataSource::SQLITE;
        
        $this->dsn = "sqlite:{$path}{$file}";
    }
    
    /**
     * Generate MySQL dsn.
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $host
     * @param string $port
     */
    public function InitAsMySql($database, $username, $password, $host='127.0.0.1', $port='3306')
    {
        $this->type = DBDataSource::MYSQL;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->dsn = "mysql:dbname=$database;host=$host;port=$port;";
    }
    
    /**
     * Generate PostgreSQL dsn.
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $host
     * @param string $port
     */
    public function InitAsPostgreSQL($database, $username, $password, $host='127.0.0.1', $port='5432')
    {
        $this->type = DBDataSource::POSTGRESQL;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->dsn = "pgsql:dbname=$database;host=$host;port=$port;";
    }
}
?>
