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
class InvalidPasswordException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Incorrect password.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
