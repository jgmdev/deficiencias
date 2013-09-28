<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class ResetField extends Field
{
    /**
     * Default constructor.
     * @param string $label
     * @param string $name
     */
    public function __construct($label, $name)
    {
        parent::__construct('', $name, $label, '', null, FormFieldType::RESET);
    }
}
?>
