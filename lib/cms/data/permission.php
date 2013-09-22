<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a permission
 */
class Permission
{
    /**
     * @var string
     */
    public $identifier;
    
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
    public $value;
    
    /**
     * Default constructor
     */
    public function __construct($identifier, $label, $description)
    {
        $this->identifier = $identifier;
        $this->label = $label;
        $this->description = $description;
        $this->value = false;
    }
}

?>
