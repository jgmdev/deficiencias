<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if trying to login with an account that hasn't been approved.
 */
class AwaitingApprovalException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('The account is awaiting approval.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
