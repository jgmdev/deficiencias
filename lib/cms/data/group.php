<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

use Cms\Data;
use Cms\Groups;

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
     * @var array
     */
    private $permission_table;
    
    /**
     * Default constructor
     * @param string $machine_name
     * @param string $name
     */
    public function __construct($machine_name='', $name='', $description='')
    {
        $this->machine_name = $machine_name;
        $this->name = $name;
        $this->description = $description;
        $this->permission_table = null;
    }
    
    /**
     * Checks if the group has a given permission.
     * @see \Cms\Enumerations\Permissions
     * @param string|array $identifer
     * @return bool
     */
    public function HasPermission($identifier)
    {
        if($this->machine_name == 'administrator')
        {
            return true;
        }

        if(!$this->permission_table)
        {
            $permissions_data_path = Groups::GetPath($this->machine_name);
            $permissions_data_path = str_replace('/data.php', '/permissions.php', $permissions_data_path);  
                    
            if(file_exists($permissions_data_path))
            {
                $data = new Data;
                $this->permission_table = $data->Parse($permissions_data_path);
            }
        }

        if(is_array($this->permission_table))
        {
            if(is_string($identifier))
            {
                if(isset($this->permission_table[0][$identifier]))
                    return trim($this->permission_table[0][$identifier]);
            }
            elseif(is_array($identifier))
            {
                if(count($identifier) > 0)
                {
                    foreach($identifier as $permission)
                    {
                        if(isset($this->permission_table[0][$permission]))
                        {
                            if(!$this->permission_table[0][$permission])
                                return false;
                        }
                        else
                        {
                            return false;
                        }
                    }
                    
                    return true;
                }
            }
        }

        return false;
    }
    
    /**
     * Enables/Disables a given permission.
     * @see \Cms\Enumerations\Permissions
     * @param string $permission_name
     * @param bool $flag
     */
    public function SetPermission($identifier, $flag)
    {
        $permissions_data_path = Groups::GetPath($this->machine_name);
        $permissions_data_path = str_replace('/data.php', '/permissions.php', $permissions_data_path);

        $data = new Data($permissions_data_path);
        $permissions_data = $data->GetRow(0);

        $permissions_data[$identifier] = $flag;

        $data->EditRow(0, $permissions_data);
    }
}

?>
