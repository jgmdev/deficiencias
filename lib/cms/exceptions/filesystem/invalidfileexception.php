<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\FileSystem;

/**
 * Exception thrown if invalid or non existant file.
 */
class InvalidFileException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Invalid or non existant file.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
