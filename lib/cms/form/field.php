<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class Field
{
    /**
     * @see \Cms\Enumerations\FormFieldType
     * @var string
     */
    public $type;
    
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $value;
    
    /**
     * @var string
     */
    public $label;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var string
     */
    public $placeholder;
    
    /**
     * @var bool
     */
    public $required;
    
    /**
     * @var bool
     */
    public $readonly;
    
    /**
     * @var int
     */
    public $size;
    
    /**
     * @var array
     */
    public $attributes;
    
    /**
     * @var \Cms\Form\Validator\Validator
     */
    public $validator;
    
    /**
     * Default constructor.
     * @param string $label
     * @param string $name
     * @param string $value
     * @param string $description
     * @param string $placeholder
     * @param string $type
     * @param bool $required
     * @param bool $readonly
     * @param int $size
     * @return \Cms\Form\Field
     */
    public function __construct($label, $name, $value='', $description='', $placeholder='', $type=FormFieldType::TEXT, $required=false, $readonly=false, $size=0)
    {
        $this->attributes = array();
        
        $this->label = $label;
        $this->id = $name;
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->size = $size;
        $this->validator = null;
        
        return $this;
    }
    
    public function AddAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        
        return $this;
    }
    
    public function RemoveAttribute($name)
    {
        if(isset($this->attributes[$name]))
            unset($this->attributes[$name]);
        
        return $this;
    }
    
    public function SetValidator(\Cms\Form\Validator\Validator $validator)
    {
        $this->validator = $validator;
        
        return $this;
    }
    
    /**
     * Check if the current value read from $_REQUEST[name] or 
     * the value poperty matches the characteristics set by 
     * the validator.
     * @see SetValidator()
     * @return boolean
     */
    public function HasValidValue()
    {
        $current_value = isset($_REQUEST[$this->name]) ? 
            $_REQUEST[$this->name] : $this->value;
        
        if(is_object($this->validator))
        {
            if(!$this->validator->IsValid($current_value))
                return false;
        }
        
        return true;
    }
    
    public function GetLabelHtml()
    {
        $html = '<label for="'.$this->id.'">';
        $html .= $this->label;
        
        if($this->required)
            $html .= ' <span class="required">*</span>';
        
        $html .= '</label>' . "\n";
        
        return $html;
    }
    
    public function GetHtml()
    {   
        $html = '<input type="'.$this->type.'" ';
        $html .= 'id="'.$this->id.'" ';
        $html .= 'name="'.$this->name.'" ';
        
        if(isset($_REQUEST[$this->name]))
            $html .= 'value="'.$_REQUEST[$this->name].'" ';
        elseif(trim($this->value) != "")
            $html .= 'value="'.$this->value.'" ';
        
        if($this->placeholder)
            $html .= 'placeholder="'.$this->placeholder.'" ';
        
        if($this->required > 0)
            $html .= 'required ';
        
        if($this->readonly > 0)
            $html .= 'readonly ';
        
        if($this->size > 0)
            $html .= 'size="'.$this->size.'" ';
        
        if(count($this->attributes) > 0)
        {
            foreach($this->attributes as $name=>$value)
            {
                $html .= $name.'="'.$value.'" ';
            }
        }
        
        $html .= '/>' . "\n";
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
}
?>
