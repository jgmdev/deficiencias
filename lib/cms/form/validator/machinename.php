<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Validator;

use Cms\Form\Validator;

/**
 * Verifies if a string is a valid machine name containing only
 * letters from a-z and underscores. Also checks the 
 * machine name is at least 3 characters long.
 */
class MachineName extends Validator
{
    /**
     * Default constructor.
     * @param string $value
     * @return \Cms\Form\Validator\MachineName
     */
    public function __construct($value = null)
    {
        parent::__construct($value);
        
        $this->pattern = '/([a-z\_])+/';
        
        $this->min_lenght = 3;
        
        return $this;
    }
}
?>