<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Form\Field;
use Cms\Enumerations\FormFieldType;

class Hidden extends Field
{
    public function __construct($name, $value)
    {
        parent::__construct('', $name, $value, '', '', FormFieldType::HIDDEN, true, false, 0);
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
