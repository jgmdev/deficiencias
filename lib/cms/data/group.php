<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a group.
 */
class Group 
{
    /**
     * @var string
     */
    public $machine_name;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $description;
    
    public function __construct($machine_name="", $name="")
    {
        $this->machine_name = $machine_name;
        $this->name = $name;
    }
    
    /**
     * Checks if the group has a given permission.
     * @param string $permission_name
     * @return bool
     */
    public function HasPermission($permission_name)
    {
        
    }
    
    /**
     * Enables/Disables a given permission.
     * @param string $permission_name
     * @param bool $flag
     */
    public function SetPermission($permission_name, $flag)
    {
        
    }
}

?>
