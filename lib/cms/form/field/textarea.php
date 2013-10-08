<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Enumerations\FormFieldType;

class TextArea extends Text
{
    public function __construct($label, $name, $value='', $description='', $placeholder='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, $placeholder, $required, $readonly, $size);
        
        $this->type = FormFieldType::TEXTAREA;
    }
    
    public function GetSingleHtml($value = '')
    {
        $html = '<textarea ';
        $html .= 'id="'.$this->id.'" ';
        $html .= 'name="'.$this->name.'" ';
        
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
            foreach($this->attributes as $attr_name=>$attr_value)
            {
                $html .= $attr_name.'="'.$attr_value.'" ';
            }
        }
        
        $html .= ">";
        
        $html .= $value;
        
        $html .= '</textarea>' . "\n";
        
        return $html;
    }
}
?>
