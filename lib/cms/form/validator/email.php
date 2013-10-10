<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Validator;

use Cms\Form\Validator;

class Email extends Validator
{
    /**
     * Default constructor.
     * @param string $value
     * @return \Cms\Form\Validator\Email
     */
    public function __construct($value = null)
    {
        parent::__construct($value);
        
        $this->pattern = '/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/';
        
        return $this;
    }
    
    /**
     * Checks if a given email address is valid.
     * @param string $value
     * @return boolean
     */
    public function IsValid($value=null)
    {
        if(!parent::IsValid($value))
            return false;
        
        //If the function is available we also check the dns record for mx entries
        if(function_exists("checkdnsrr"))
        {
            list($name, $domain) = explode('@', $this->value);

            if(!checkdnsrr($domain, 'MX'))
            {
                return false;
            }
        }

        return true;
    }
}
?>