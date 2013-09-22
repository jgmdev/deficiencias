<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class PasswordField extends Field
{
    public function __construct($label, $name, $value='', $description='', $placeholder='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, $placeholder, FormFieldType::PASSWORD, $required, $readonly, $size);
    }
    
    /**
     * @todo Add jquery plugin to dinamycally show characters left when size > 0.
     */
    public function GetHtml()
    {
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
        
        $html = parent::GetHtml();
        
        return $html;
    }
}
?>
