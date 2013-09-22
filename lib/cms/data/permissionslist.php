<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a list of permissions. This class should be extended to implement
 * groups of permissions.
 */
class PermissionsList
{
    /**
     * Name of the list.
     * @var string
     */
    public $name;
    
    /**
     * Description of permissions.
     * @var string
     */
    public $description;
    
    /**
     * @var \Cms\Data\Permission[]
     */
    private $permissions;
    
    /**
     * Default constructor.
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description='')
    {
        $this->name = $name;
        $this->description = $description;
        
        $this->permissions = array();
    }
    
    /**
     * Add permission to the list.
     * @param \Cms\Data\Permission $permission
     */
    public function AddPermission($permission)
    {
        $this->permissions[$permission->identifier] = $permission;
    }
    
    /**
     * Remove permission from the list.
     * @param string $identifier
     */
    public function RemovePermission($identifier)
    {
        unset($this->permissions[$identifier]);
    }
    
    /**
     * Checks if a permission is available and set its value by the given flag.
     * @param string $identifer
     * @param boolean $flag
     * @return boolean True if permission was found, false otherwise.
     */
    public function SetPermission($identifer, $flag)
    {
        if(isset($this->permissions[$identifer]))
        {
            $this->permissions[$identifer]->value = $flag;
            
            return true;
        }
        
        return false;
    }
    
    public function SetAllPermissionsTrue()
    {
        foreach($this->permissions as &$permission)
        {
            $permission->value = true;
        }
    }
    
    /**
     * @return \Cms\Data\Permission[]
     */
    public function GetAll()
    {
        return $this->permissions;
    }
}

?>
