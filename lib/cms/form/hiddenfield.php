<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class HiddenField extends Field
{
    public function __construct($name, $value)
    {
        parent::__construct('', $name, $value, '', '', FormFieldType::HIDDEN, false, false, 0);
    }
    
    public function GetLabelHtml()
    {
        return '';
    }
    
    public function GetHtml()
    {
        $html = '<input type="'.$this->type.'" ';
        $html .= 'id="'.$this->id.'" ';
        $html .= 'name="'.$this->name.'" ';
        
        if(trim($this->value) != "")
            $html .= 'value="'.$this->value.'" ';
        
        if(count($this->attributes) > 0)
        {
            foreach($this->attributes as $name=>$value)
            {
                $html .= $name.'="'.$value.'" ';
            }
        }
        
        $html .= '/>' . "\n";
        
        return $html;
    }
}
?>
