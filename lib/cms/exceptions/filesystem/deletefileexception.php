<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\FileSystem;

/**
 * Exception thrown when deleting a file fails.
 */
class DeleteFileException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Could not delete the file.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
