<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Exception thrown if a new user account is being created 
 * with an already existant username.
 */
class UserExistsException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('A user account with the given username already exists.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
