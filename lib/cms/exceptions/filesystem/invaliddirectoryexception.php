<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\FileSystem;

/**
 * Exception for an invalid directory path.
 */
class InvalidDirectoryException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Invalid directory path.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
