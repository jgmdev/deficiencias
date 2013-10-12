<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Enumerations\FormFieldType;

class Password extends Text
{
    public function __construct(
        $label, $name, $value='', $description='', $placeholder='', 
        $required=false, $readonly=false, $size=0
    )
    {
        parent::__construct($label, $name, $value, $description, $placeholder, $required, $readonly, $size);
        
        $this->type = FormFieldType::PASSWORD;
    }
}
?>
