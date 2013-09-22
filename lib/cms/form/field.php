<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class Field
{
    public $type;
    
    public $id;
    
    public $name;
    
    public $value;
    
    public $label;
    
    public $description;
    
    public $required;
    
    public $readonly;
    
    public $size;
    
    public $attributes;
    
    public $type;
    
    public $validator;
    
    public function __construct($label, $name, $value, $description="", $type=FormFieldType::TEXT, $required=false, $readonly=false, $size=0)
    {
        $this->attributes = array();
        
        $this->label = $label;
        $this->id = $name;
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->type = $type;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->size = $size;
        
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
    
    public function HasValidValue()
    {
        
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
            $html .= '<div class="description">'.$this->description.'</div>';
        
        return $html;
    }
}
?>
