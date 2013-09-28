<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

use Cms\Enumerations\DBDataSource;

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
    
    public function TableExists($name)
    {
        $this->VerifyIsConnected();
        
        switch($this->type)
        {
            case DBDataSource::SQLITE:
            {
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
    
    public function CreateTable(\Cms\DBAL\Query\Table $table)
    {
        return $this->Exec($table);
    }
    
    public function Insert(\Cms\DBAL\Query\Insert $insert)
    {
        return $this->Exec($insert);
    }
    
    public function Update(\Cms\DBAL\Query\Update $update)
    {
        return $this->Exec($update);
    }
    
    public function Delete(\Cms\DBAL\Query\Delete $delete)
    {
        return $this->Exec($delete);
    }
    
    public function Select(\Cms\DBAL\Query\Select $select)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($select->GetSQL($this->type));
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    public function Count(\Cms\DBAL\Query\Count $select)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($select->GetSQL($this->type));
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
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
    
    public function CustomQuery($sql)
    {
        $this->VerifyIsConnected();
        
        $this->pdo_statement = $this->pdo->query($sql);
        
        if($this->pdo_statement)
            return true;
        
        return false;
    }
    
    public function GetError()
    {
        return $this->pdo->errorInfo();
    }
    
    public function Fetch()
    {
        $this->VerifyIsConnected();
        
        if($this->pdo_statement)
            return $this->pdo_statement->fetch();
        
        return false;
    }
    
    public function FetchArray()
    {
        $this->VerifyIsConnected();
        
        if($this->pdo_statement)
            return $this->pdo_statement->fetch(\PDO::FETCH_ASSOC);
        
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
