<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if a user login action is being executed 
 * with a password that doesn't matches.
 */
class InvalidPasswordException extends \Exception {}

?>
