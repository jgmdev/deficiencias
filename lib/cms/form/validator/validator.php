<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Validator;

use Cms\Enumerations\ValidatorError;

/**
 * Assist of the validation of form entered data.
 */
class Validator
{
    /**
     * @var int
     */
    public $min_lenght;
    
    /**
     * @var int
     */
    public $max_lenght;
    
    /**
     * @var array
     */
    public $valid_values;
    
    /**
     * Regular expression to be evalualted agains the given
     * value using the preg_match() function.
     * @var string
     */
    public $pattern;
    
    /**
     * Current value to validate.
     * @var string
     */
    public $value;
    
    /**
     * List of error messages produced while checking if value is valid.
     * @var array
     */
    public $errors;
    
    /**
     * Default constructor.
     * @param string $value
     * @return \Cms\Form\Validator\Validator
     */
    public function __construct($value=null)
    {
        $this->min_lenght = null;
        $this->max_lenght = null;
        $this->valid_values = array();
        $this->pattern = null;
        $this->errors = array();
        
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * The minimum lenght the value could be.
     * @param int $lenght
     * @return \Cms\Form\Validator\Validator
     */
    public function SetMinLenght($lenght)
    {
        $this->min_lenght = $lenght;
        
        return $this;
    }
    
    /**
     * The maximum lenght the value could be.
     * @param int $lenght
     * @return \Cms\Form\Validator\Validator
     */
    public function SetMaxLenght($lenght)
    {
        $this->max_lenght = $lenght;
        
        return $this;
    }
    
    /**
     * Array of valid values.
     * @param array $values
     * @return \Cms\Form\Validator\Validator
     */
    public function SetValidValues(array $values)
    {
        $this->valid_values = $values;
        
        return $this;
    }
    
    /**
     * A regular expression to match agains the value.
     * @param type $pattern
     * @return \Cms\Form\Validator\Validator
     */
    public function SetPattern($pattern)
    {
        $this->pattern = $pattern;
        
        return $this;
    }
    
    /**
     * Check if a given error was encounter while checking if valid.
     * @see \Cms\Enumerations\ValidatorError
     * @param string $error
     */
    public function HasError($error)
    {
        if(isset($this->errors[$error]))
            return true;
        
        return false;
    }
    
    /**
     * Checks if a given value or already set on constructor passes the
     * validation rules.
     * @param string $value
     * @return boolean
     */
    public function IsValid($value=null)
    {
        if($value != null)
            $this->value = trim($value);
        
        //Remove previous errors
        $this->errors = array();
        
        if(!$this->ValidMinLenght())
        {
            $this->errors[ValidatorError::MIN_LENGHT] = true;
            return false;
        }
        
        if(!$this->ValidMaxLenght())
        {
            $this->errors[ValidatorError::MAX_LENGHT] = true;
            return false;
        }

        if(!$this->ValidValue())
        {
            $this->errors[ValidatorError::VALID_VALUE] = true;
            return false;
        }
        
        if(!$this->ValidPattern())
        {
            $this->errors[ValidatorError::PATTERN] = true;
            return false;
        }

        return true;
    }
    
    /**
     * @return boolean
     */
    protected function ValidMinLenght()
    {
        if($this->min_lenght == null)
            return true;
        
        if(strlen($this->value) >= $this->min_lenght)
            return true;
        
        return false;
    }
    
    /**
     * @return boolean
     */
    protected function ValidMaxLenght()
    {
        if($this->max_lenght == null)
            return true;
        
        if(strlen($this->value) <= $this->max_lenght)
            return true;
        
        return false;
    }
    
    /**
     * @return boolean
     */
    protected function ValidValue()
    {
        if(count($this->valid_values) <= 0)
            return true;
        
        if(in_array($this->value, $this->valid_values))
            return true;
        
        return false;
    }
    
    /**
     * @return boolean
     */
    protected function ValidPattern()
    {
        if(!$this->pattern)
            return true;
        
        if(preg_match($this->pattern, $this->value))
            return true;
        
        return false;
    }
}
?>
