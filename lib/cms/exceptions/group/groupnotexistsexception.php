<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Group;

/**
 * Exception thrown if a group operation is being executed 
 * on a non existant group.
 */
class GroupNotExistsException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('The group does not exists.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
