<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

/**
 * Basic handling of databases while hiding the sql differences of each provider.
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
     * @var string @see \Cms\DBAL\DataSource
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
    
    public function __construct(\Cms\DBAL\DataSource $datasource=null)
    {
        if($datasource)
            $this->Connect($datasource);
    }
    
    public function __destruct()
    {
        $this->Disconnect();
    }
    
    public function Connect(\Cms\DBAL\DataSource $datasource=null)
    {
        $this->Disconnect();
        
        $this->datasource = clone $datasource;
        
        $this->type = $datasource->type;
        
        $this->connected = true;
        
        switch($datasource->type)
        {
            case DataSource::SQLITE:
                $this->pdo = new \PDO($datasource->GetDSN());
                break;
            
            case DataSource::MYSQL:
            case DataSource::POSTGRESQL:
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
    
    public function Disconnect()
    {
        $this->connected = false;
        
        if($this->pdo)
            unset($this->pdo);
        
        if($this->pdo_statement)
            unset($this->pdo_statement);
    }
    
    public function Reconnect()
    {
        $this->Connect($this->datasource);
    }
    
    public function IsConnected()
    {
        return $this->connected;
    }
    
    public function CreateTable(\Cms\DBAL\Query\Table $table)
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->exec($table->GetSQL($this->type));
    }
    
    public function Insert(\Cms\DBAL\Query\Insert $insert)
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->exec($insert->GetSQL($this->type));
    }
    
    public function Update(\Cms\DBAL\Query\Update $update)
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->exec($update->GetSQL($this->type));
    }
    
    public function Delete(\Cms\DBAL\Query\Delete $delete)
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->exec($delete->GetSQL($this->type));
    }
    
    public function Select(\Cms\DBAL\Query\Select $select)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($select->GetSQL($this->type));
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    public function CustomExec($sql)
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->exec($sql);
    }
    
    public function CustomQuery($sql)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($sql);
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    public function Fetch()
    {
        $this->VerifyIsConnected();
        
        if($this->pdo_statement)
            return $this->pdo_statement->fetch();
        
        return false;
    }
    
    public function LastInsertID()
    {
        $this->VerifyIsConnected();
        
        return $this->pdo->lastInsertId();
    }
    
    private function VerifyIsConnected()
    {
        if(!$this->connected)
            throw new Exception (t('No connection establised to carry out this operation.'));
    }
}

?>
