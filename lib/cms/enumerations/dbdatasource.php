<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations;

/**
 * Databases backends that can be used to establish a data connection using the
 * database abstract layer.
 */
class DBDataSource
{
    const SQLITE = 'sqlite';
    const MYSQL = 'mysql';
    const POSTGRESQL = 'postgresql';
}
?>
