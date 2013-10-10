<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\FileSystem;

/**
 * Exception thrown if moving a file failed.
 */
class MoveFileException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Could not move the file.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
