<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

use Cms\Enumerations\DBDataSource;

/**
 * Serves as interface for query implementations.
 */
class Query
{   
    /**
     * Generates the sql code to create a table depending on database type.
     * @param string $type One of the constants from \Cms\DBAL\DataSource
     */
    public function GetSQL($type)
    {
        switch($type)
        {
            case DBDataSource::SQLITE;
                return $this->GetSQLiteSQL();
                
            case DBDataSource::MYSQL;
                return $this->GetMySqlSQL();
                
            case DBDataSource::POSTGRESQL;
                return $this->GetPostgreSQL();
        }
    }
    
    /**
     * Generate SQLite SQL.
     * @throws Exception
     */
    protected function GetSQLiteSQL()
    {
        throw new \Exception('Not implemented');
    }
    
    /**
     * Generate MySQL SQL.
     * @throws Exception
     */
    protected function GetMySqlSQL()
    {
        throw new \Exception('Not implemented');
    }
    
    /**
     * Generate PostgreSQL SQL.
     * @throws Exception
     */
    protected function GetPostgreSQL()
    {
        throw new \Exception('Not implemented');
    }
}
?>
