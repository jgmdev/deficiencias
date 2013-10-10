<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Validator;

use Cms\Form\Validator;

/**
 * Verifies if a username is valid and contains only letters, 
 * numbers, dots and dashes. Also checks the username is at least
 * 3 characters long.
 */
class Username extends Validator
{
    /**
     * Default constructor.
     * @param string $value
     * @return \Cms\Form\Validator\Username
     */
    public function __construct($value = null)
    {
        parent::__construct($value);
        
        $this->pattern = '/\w+/';
        
        $this->min_lenght = 3;
        
        return $this;
    }
}
?>