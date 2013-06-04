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
     * @param bool $value
     */
    public function SetPermission($permission_name, $value)
    {
        
    }
}

?>
