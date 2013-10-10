<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Form;

/**
 * Exception thrown when adding an empty fields group to a form.
 */
class EmptyFieldsGroupException extends \Exception 
{
    public function __construct($message=null, $code=0, $previous=null)
    {
        if($message == null)
            $message = t('Trying to add an empty fields group.');
        
        parent::__construct($message, $code, $previous);
    }
}

?>
