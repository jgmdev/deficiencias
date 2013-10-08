<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

use Cms\Enumerations\DBDataSource;

/**
 * Abstract database layer that encapsulates \PDO to hide the sql code 
 * differences of each database provider.
 */
class DataBase
{
    /**
     * The database handler
     * @var \PDO
     */
    public $pdo;
    
    /**
     * Handler of a query result.
     * @var \PDOStatement
     */
    public $pdo_statement;
    
    /**
     * Type of database we are connected to.
     * @var string @see \Cms\Enumerations\DBDataSource
     */
    public $type;
    
    /**
     * Copy of the data source used to initialize the connection.
     * @var \Cms\DBAL\DataSource 
     */
    public $datasource;
    
    /**
     * Flag that inidicates connection status.
     * @var boolean 
     */
    private $connected;
    
    /**
     * Default constructor.
     * @param \Cms\DBAL\DataSource $datasource
     */
    public function __construct(\Cms\DBAL\DataSource $datasource=null)
    {
        if($datasource)
            $this->Connect($datasource);
    }
    
    /**
     * Closes connection if still open.
     */
    public function __destruct()
    {
        $this->Disconnect();
    }
    
    /**
     * Closes any previous connection and opens a new one using the new
     * given data source.
     * @param \Cms\DBAL\DataSource $datasource
     * @throws Exception
     */
    public function Connect(\Cms\DBAL\DataSource $datasource=null)
    {
        $this->Disconnect();
        
        $this->datasource = clone $datasource;
        
        $this->type = $datasource->type;
        
        $this->connected = true;
        
        switch($datasource->type)
        {
            case DBDataSource::SQLITE:
                $this->pdo = new \PDO($datasource->GetDSN());
                break;
            
            case DBDataSource::MYSQL:
            case DBDataSource::POSTGRESQL:
                $this->pdo = new \PDO(
                    $datasource->GetDSN(), 
                    $datasource->username, 
                    $datasource->password
                );
                break;
            
            default:
                throw new Exception(t('Invalid data source.'));
        }
    }
    
    /**
     * Closes current connection.
     */
    public function Disconnect()
    {
        $this->connected = false;
        
        if($this->pdo)
            unset($this->pdo);
        
        if($this->pdo_statement)
            unset($this->pdo_statement);
    }
    
    /**
     * Re-establish connection to a previous connection that was closed.
     */
    public function Reconnect()
    {
        $this->Connect($this->datasource);
    }
    
    /**
     * Connection status.
     * @return boolean True if connected.
     */
    public function IsConnected()
    {
        return $this->connected;
    }
    
    /**
     * Check if a given table name exists on the database.
     * @param string $name
     * @return boolean True if exists.
     */
    public function TableExists($name)
    {
        $this->VerifyIsConnected();
        
        switch($this->type)
        {
            case DBDataSource::SQLITE:
            {
                $name = str_replace("'", "''", $name);
                
                $this->CustomQuery("SELECT name FROM sqlite_master WHERE type='table' AND name='$name'");
                
                $data = $this->FetchArray();
                
                if(isset($data['name']))
                    return true;
                
                break;
            }
            case DBDataSource::MYSQL:
            {
                throw new \Exception(t('Not implemented'));
                break;
            }
            case DBDataSource::POSTGRESQL:
            {
                throw new \Exception(t('Not implemented'));
                break;
            }
            default:
                throw new \Exception(t('Not implemented'));
        }
        
        return false;
    }
    
    /**
     * Execute a create table sql query.
     * @param \Cms\DBAL\Query\Table $table
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function CreateTable(\Cms\DBAL\Query\Table $table)
    {
        return $this->Exec($table);
    }
    
    /**
     * Execute an insert row sql query.
     * @param \Cms\DBAL\Query\Insert $insert
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function Insert(\Cms\DBAL\Query\Insert $insert)
    {
        return $this->Exec($insert);
    }
    
    /**
     * Execute a row update sql query.
     * @param \Cms\DBAL\Query\Update $update
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function Update(\Cms\DBAL\Query\Update $update)
    {
        return $this->Exec($update);
    }
    
    /**
     * Execute a row delete sql query.
     * @param \Cms\DBAL\Query\Delete $delete
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function Delete(\Cms\DBAL\Query\Delete $delete)
    {
        return $this->Exec($delete);
    }
    
    /**
     * Execute a rows selection sql query.
     * @param \Cms\DBAL\Query\Select $select
     * @return boolean True if successfull.
     */
    public function Select(\Cms\DBAL\Query\Select $select)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($select->GetSQL($this->type));
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    /**
     * Execute a count sql query.
     * @param \Cms\DBAL\Query\Count $select
     * @return boolean True if successfull.
     */
    public function Count(\Cms\DBAL\Query\Count $select)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($select->GetSQL($this->type));
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    /**
     * Execute any kind of sql query.
     * @param \Cms\DBAL\Query $query
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function Exec(\Cms\DBAL\Query $query)
    {
        $this->VerifyIsConnected();
        
        $value = $this->pdo->exec($query->GetSQL($this->type));
        
        if($value === false)
        {
            $error = $this->GetError();
            throw new \Exception($error[2], $error[1]);
        }
        
        return $value;
    }
    
    /**
     * Directly execute sql code without using the \Cms\DBAL\Query abstract layer.
     * @param string $sql
     * @return boolean True if successfull.
     * @throws \Exception In case of syntax errors @see GetError()
     */
    public function CustomExec($sql)
    {
        $this->VerifyIsConnected();
        
        $value = $this->pdo->exec($sql);
        
        if($value === false)
        {
            $error = $this->GetError();
            throw new \Exception($error[2], $error[1]);
        }
        
        return $value;
    }
    
    /**
     * Directly execute sql code that generate row results without using 
     * the \Cms\DBAL\Query abstract layer.
     * @param string $sql
     * @return boolean True if successfull.
     */
    public function CustomQuery($sql)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($sql);
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    /**
     * Get the las errors produced by a previous query.
     * @return array
     */
    public function GetError()
    {
        return $this->pdo->errorInfo();
    }
    
    /**
     * Fetch one of the table rows returned by a previous query.
     * @return array|boolean Array of row columns including numeric indices
     * or false if theres no rows to read.
     */
    public function Fetch()
    {
        $this->VerifyIsConnected();
        
        if($this->pdo_statement)
            return $this->pdo_statement->fetch();
        
        return false;
    }
    
    /**
     * Fetch one of the table rows returned by a previous query.
     * @return array|boolean Associative array of row columns 
     * including numeric indices or false if theres no rows to read.
     */
    public function FetchArray()
    {
        $this->VerifyIsConnected();
        
        if($this->pdo_statement)
            return $this->pdo_statement->fetch(\PDO::FETCH_ASSOC);
        
        return false;
    }
    
    /**
     * Get the value of last inserted id.
     * @return type
     */
    public function LastInsertID()
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Assistive function called on operations that require an active connection.
     * @throws Exception When a database connection isn't currently established.
     */
    private function VerifyIsConnected()
    {
        if(!$this->connected)
            throw new Exception (t('No connection establised to carry out this operation.'));
    }
}

?>
