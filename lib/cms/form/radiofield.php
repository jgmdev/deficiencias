<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class RadioField extends Field
{
    public function __construct($label, $name, $value='', $description='', $placeholder='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, $placeholder, FormFieldType::RADIO, $required, $readonly, $size);
    }
}
?>
