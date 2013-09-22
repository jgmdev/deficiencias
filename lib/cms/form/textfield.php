<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class TextField extends Field
{
    public function __construct($label, $name, $value, $description="", $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, FormFieldType::TEXT, $required, $readonly, $size);
    }
    
    /**
     * @todo Add jquery plugin to dinamycally show characters left when size > 0.
     */
    public function GetHtml()
    {
        $html = parent::GetHtml();
        
        return $html;
    }
}
?>
