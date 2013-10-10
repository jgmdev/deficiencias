<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Group;

/**
 * Exception thrown when trying to modify a system group in
 * ways that aren't permitted.
 */
class SystemGroupException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Could not modify system group.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
