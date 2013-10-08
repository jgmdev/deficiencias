<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

/**
 * Interface for filtering a given value.
 */
class Filter
{
    /**
     * Current value to filter.
     * @var string
     */
    public $value;
    
    /**
     * Default constructor.
     * @param string $value
     * @return \Cms\Form\Filter
     */
    public function __construct($value='')
    {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * Set the internal value that is going to be filtered.
     * @param type $value
     * @return \Cms\Form\Filter
     */
    public function SetValue($value)
    {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * Filters the value removing unneccesary stuff. By default this method
     * Just trims the original value. Use another implementation for
     * different functionality.
     * @param string $value If value is omitted previous set value is used.
     * @return string
     */
    public function GetFiltered($value=null)
    {
        if($value != null)
            $this->SetValue($value);
        
        return trim($this->value);
    }
}
?>
