<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if trying to login with an account that hasn't been approved.
 */
class AwaitingApprovalException extends \Exception {}

?>
