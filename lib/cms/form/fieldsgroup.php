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
     * @var \Cms\Form\Field[] 
     */
    public $fields;
    
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
    public function __construct($label, $description='', $collapsed=false)
    {
        $this->label = $label;
        $this->description = $description;
        $this->collapsed = $collapsed;
        $this->fields = array();
    }
    
    /**
     * Add a new field to the internal fields array.
     * @param \Cms\Form\Field $field
     */
    public function AddField(\Cms\Form\Field $field)
    {
        $this->fields[] = $field;
    }
}
?>
