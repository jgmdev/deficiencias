<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class SubmitField extends Field
{   
    /**
     * Default constructor.
     * @param string $label
     * @param string $name
     */
    public function __construct($label, $name)
    {
        parent::__construct('', $name, $label, '', null, FormFieldType::SUBMIT);
    }
}
?>
