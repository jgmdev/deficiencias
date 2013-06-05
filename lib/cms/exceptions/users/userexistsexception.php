<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if a new user account is being created with an already
 * existant username.
 */
class UserExistsException extends \Exception {}

?>
