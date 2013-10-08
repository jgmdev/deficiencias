<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\DBAL;

use Cms\Enumerations\FieldType;
use Cms\Enumerations\DBDataSource;

/**
 * Serves as interface for sql abstracted implementations.
 */
class Query
{   
    const BEGIN_EXPRESSION = 1;
    const END_EXPRESSION = 2;
    const WHERE = 3;
    const ORDER_BY = 4;
    const OR_EXPRESSION = 5;
    const AND_EXPRESSION = 6;
    
    /**
     * Flag enabled when the query has a where statement.
     * @see HasWhere()
     * @var boolean
     */
    private $has_where;
    
    /**
     * @var array
     */
    public $operations = array();
    
    /**
     * Add a ( to start grouping some expression.
     * @return \Cms\DBAL\Query
     */
    public function BeginExpr()
    {
        $this->operations[] = array(
            'code'=>self::BEGIN_EXPRESSION
        );
        
        return $this;
    }
    
    /**
     * Add a ) to end some expression.
     * @return \Cms\DBAL\Query
     */
    public function EndExpr()
    {
        $this->operations[] = array(
            'code'=>self::END_EXPRESSION
        );
        
        return $this;
    }
    
    /**
     * Add AND operator.
     * @return \Cms\DBAL\Query
     */
    public function AndOp()
    {
        $this->operations[] = array(
            'code'=>self::AND_EXPRESSION
        );
        
        return $this;
    }
    
    /**
     * Add OR operator.
     * @return \Cms\DBAL\Query
     */
    public function OrOp()
    {
        $this->operations[] = array(
            'code'=>self::OR_EXPRESSION
        );
        
        return $this;
    }
    
    /**
     * Starts an == where statement.
     * @param string $column
     * @param string $value
     * @param string $type @see \Cms\Enumerations\FieldType
     * @return \Cms\DBAL\Query
     */
    public function WhereEqual($column, $value, $type)
    {
        $this->has_where = true;
        
        $this->operations[] = array(
            'code'=>self::WHERE,
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'='
        );
        
        return $this;
    }
    
    /**
     * Starts an != where statement.
     * @param string $column
     * @param string $value
     * @param string $type @see \Cms\Enumerations\FieldType
     * @return \Cms\DBAL\Query
     */
    public function WhereNotEqual($column, $value, $type)
    {
        $this->has_where = true;
        
        $this->operations[] = array(
            'code'=>self::WHERE,
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'!='
        );
        
        return $this;
    }
    
    /**
     * Starts an > where statement.
     * @param string $column
     * @param string $value
     * @param string $type @see \Cms\Enumerations\FieldType
     * @return \Cms\DBAL\Query
     */
    public function WhereMoreThan($column, $value, $type)
    {
        $this->has_where = true;
        
        $this->operations[] = array(
            'code'=>self::WHERE,
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'>'
        );
        
        return $this;
    }
    
    /**
     * Starts an < where statement.
     * @param string $column
     * @param string $value
     * @param string $type @see \Cms\Enumerations\FieldType
     * @return \Cms\DBAL\Query
     */
    public function WhereLessThan($column, $value, $type)
    {
        $this->has_where = true;
        
        $this->operations[] = array(
            'code'=>self::WHERE,
            'column'=>$column,
            'value'=>$value,
            'type'=>$type,
            'op'=>'<'
        );
        
        return $this;
    }
    
    /**
     * Starts a custom where statement.
     * @param string $statement Custom sql code.
     * @return \Cms\DBAL\Query
     */
    public function WhereCustom($statement)
    {
        $this->has_where = true;
        
        $this->operations[] = array(
            'code'=>self::WHERE,
            'value'=>$statement,
            'type'=>'custom',
        );
        
        return $this;
    }
    
    /**
     * Check if the query has a where statement.
     * @return boolean
     */
    public function HasWhere()
    {
        return $this->has_where;
    }
    
    /**
     * Add ordering statement to the query.
     * @param string $column
     * @param integer $sort @see \Cms\Enumerations\Sort
     * @return \Cms\DBAL\Query
     */
    public function OrderBy($column, $sort=\Cms\Enumerations\Sort::ASCENDING)
    {
        $this->operations[] = array(
            'code'=>self::ORDER_BY,
            'column'=>$column,
            'sort'=>$sort,
            'custom'=>false
        );
        
        return $this;
    }
    
    /**
     * Add a custom ordering statement to the query.
     * @param string $column
     * @param integer $sort @see \Cms\Enumerations\Sort
     * @return \Cms\DBAL\Query
     */
    public function OrderByCustom($statement, $sort=\Cms\Enumerations\Sort::ASCENDING)
    {
        $this->operations[] = array(
            'code'=>self::ORDER_BY,
            'statement'=>$statement,
            'sort'=>$sort,
            'custom'=>true
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
    
    /**
     * Generate SQL code for where and order by operations.
     * @return string
     */
    protected function GetSQLiteOperations()
    {
        $sql = '';
        
        $where_statement = false;
        $order_by_statement = false;
        
        foreach($this->operations as $operation)
        {
            if($operation['code'] == self::BEGIN_EXPRESSION)
            {
                $sql .= ' (';
            }
            elseif($operation['code'] == self::END_EXPRESSION)
            {
                $sql .= ') ';
            }
            elseif($operation['code'] == self::AND_EXPRESSION)
            {
                $sql .= ' and ';
            }
            elseif($operation['code'] == self::OR_EXPRESSION)
            {
                $sql .= ' or ';
            }
            elseif($operation['code'] == self::WHERE)
            {
                if(!$where_statement)
                {
                    $sql .= ' where ';
                    $where_statement = true;
                }

                if($operation['type'] != 'custom')
                {
                    $sql .= $operation['column'] . ' ' . $operation['op'] . ' ';
                }

                switch($operation['type'])
                {
                    case FieldType::BOOLEAN:
                        $sql .= ($operation['value']?1:0);
                        break;

                    case FieldType::INTEGER:
                        $sql .= intval($operation['value']);
                        break;

                    case FieldType::REAL:
                        $sql .= doubleval($operation['value']);
                        break;

                    case FieldType::TEXT:
                        $sql .= "'" . str_replace("'", "''", $operation['value'])."'";
                        break;
                    
                    case 'custom':
                        $sql .= $operation["value"] . ' ';
                        break;
                }
            }
            elseif($operation['code'] == self::ORDER_BY)
            {
                if(!$order_by_statement)
                {
                    $sql .= ' order by ';
                    $order_by_statement = true;
                }
                else
                {
                    $sql .= ',';
                }

                if($operation['custom'])
                {
                    $sql .= $operation['statement'] . ' ';
                }
                else
                {
                    $sql .= $operation['column'] . ' ';
                }

                if($operation['sort'] == \Cms\Enumerations\Sort::ASCENDING)
                    $sql .= 'asc ';
                else
                    $sql .= 'desc ';
            }
        }
        
        return $sql;
    }
    
    protected function GetMySqlOperations()
    {
        throw new \Exception('Not implemented');
    }
    
    protected function GetPostgreSqlOperations()
    {
        throw new \Exception('Not implemented');
    }
}
?>
