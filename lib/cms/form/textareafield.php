<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class TextAreaField extends Field
{
    public function __construct($label, $name, $value='', $description='', $placeholder='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, $placeholder, FormFieldType::TEXT, $required, $readonly, $size);
    }
    
    /**
     * @todo Add jquery plugin to dinamycally show characters left when size > 0.
     */
    public function GetHtml()
    {
        \Cms\Theme::AddScript('scripts/optional/jquery.textarearesizer.js');
        
        \Cms\Theme::AddRawScript(
            '$(document).ready(function(){' . "\n" .
            "\t" . '$("textarea.form-textarea:not(.processed)").TextAreaResizer();' . "\n" .
            '});'
        );
        
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
            foreach($this->attributes as $name=>$value)
            {
                $html .= $name.'="'.$value.'" ';
            }
        }
        
        $html .= ">";
        
        if(isset($_REQUEST[$this->name]))
            $html .= $_REQUEST[$this->name];
        elseif(trim($this->value) != "")
            $html .= $this->value;
        
        $html .= '</textarea>' . "\n";
        
        if($this->size > 0)
        {
            \Cms\Theme::AddScript('scripts/optional/jquery.limit.js');
            
            $this->description .= "\n" . 
                '<span class="chars-left" id="'.$this->id.'-limit">' . 
                $this->size . 
                '</span> ' .
                '<span class="chars-left-label">' . 
                t('characters left') . 
                '</span>' . "\n"
            ;
                
            \Cms\Theme::AddRawScript(
                '$(document).ready(function(){' . "\n" .
                "\t" . '$("#'.$this->id.'").limit("'.$this->size.'", "#'.$this->id.'-limit");' . "\n" .
                '});'
            ); 
        }
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
}
?>
