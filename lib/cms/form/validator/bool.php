<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Validator;

use Cms\Form\Validator;

/**
 * Check if a given value is a boolean.
 */
class Bool extends Validator
{
    public function IsValid($value)
    {
        if(!parent::IsValid($value))
            return false;
        
        if(
            filter_var(
                $value, 
                FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE
            ) == null
        )
            return false;
        
        return true;
    }
}
?>
