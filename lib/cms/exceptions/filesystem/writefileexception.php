<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\FileSystem;

/**
 * Exception thrown when writing to a file failed.
 */
class WriteFileException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Could not write to file.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
