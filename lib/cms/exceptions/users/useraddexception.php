<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Users;

/**
 * Thrown when failing to add a user.
 */
class UserAddException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Could not add the user account.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
