<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Group;

/**
 * Exception thrown when trying to create a group with
 * a machine name of a group that already exists.
 */
class GroupExistsException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('The group already exists.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
