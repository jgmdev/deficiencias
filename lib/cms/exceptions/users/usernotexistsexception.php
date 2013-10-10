<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if a user operation is being executed on a non existant
 * user account.
 */
class UserNotExistsException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('The given user account does not exists.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
