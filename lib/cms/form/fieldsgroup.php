<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

class FieldsGroup
{
    /**
     * @var string
     */
    public $label;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var bool
     */
    public $collapsed;
    
    /**
     * Default constructor
     * @param string $label
     * @param string $description
     * @param bool $collapsed
     */
    public function __construct($label, $description, $collapsed)
    {
        $this->label = $label;
        $this->description = $description;
        $this->collapsed = $collapsed;
    }
    
    public function AddField(Field $field);
}
?>
