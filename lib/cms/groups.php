<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

use Cms\System;

/**
 * Functions to handle groups
 */
class Groups {

    /**
     * Disable constructor
     */
    private function __construct(){}

    /**
     * Adds a new group to the system.
     * @param \Cms\Data\Group $group
     * @throws \Cms\Exceptions\Group\GroupExistsException
     */
    public static function Add(\Cms\Data\Group $group)
    {
        $group_name = $group->machine_name;
        $group_data_path = self::GetPath($group_name);

        //Check if group file already exist
        if (!file_exists($group_data_path))
        {
            //Create group directory
            FileSystem::MakeDir(System::GetDataPath() . "groups/$group_name", 0755, true);

            $group_data = new Data($group_data_path);

            $row = (array) $group;
            $group_data->AddRow($row);

            //Create user group directory
            FileSystem::MakeDir(System::GetDataPath() . "users/$group_name", 0755, true);
        }
        else
        {
            throw new Exceptions\Group\GroupExistsException;
        }
    }

    /**
     * Deletes an existing group.
     * @param string $group_name The machine readable group to delete.
     * @return string "true" string on success or error message on fail.
     * @throws \Cms\Exceptions\Group\GroupNotExistsException
     * @throws \Cms\Exceptions\Group\SystemGroupException
     */
    public static function Delete($group_name) {
        $group_data_path = self::GetPath($group_name);

        if (!file_exists($group_data_path))
            throw new Exceptions\Group\GroupNotExistsException;

        //Check if group is not from system
        if ($group_name != 'administrator' && $group_name != 'regular' && $group_name != 'guest') {
            //Delete group files
            if(!FileSystem::RecursiveRemoveDir(System::GetDataPath() . "groups/$group_name"))
            {
                throw new Exceptions\FileSystem\WriteFileException;
            }

            //Move existing users from deleted group to regular group
            FileSystem::RecursiveMoveDir(System::GetDataPath() . "users/$group_name", System::GetDataPath() . 'users/regular');

            //Delete users group directory
            FileSystem::RecursiveRemoveDir(System::GetDataPath() . "users/$group_name");
        }
        else
        {
            //This is a system group and can not be deleted
            throw new Exceptions\FileSystem\SystemGroupException;
        }
    }

    /**
     * Edits or changes the data of an existing group.
     * @param string $group_name
     * @param \Cms\Data\Group $group_data
     */
    public static function Edit($group_name, $group_data)
    {
        $group_data_path = self::GetPath($group_name);

        $data = new Data($group_data_path);
        $data->EditRow(0, $group_data);

        //Check if group is not from system
        if ($group_name != 'administrator' && $group_name != 'regular' && $group_name != 'guest')
        {
            //If a new machine readable group name is passed make appropriate changes.
            if($group_data->machine_name != "" && $group_data->machine_name != $group_name)
            {
                //If the new group name already exist skip
                if (file_exists(System::GetDataPath() . "groups/{$group_data->machine_name}"))
                {
                    throw new Exceptions\Group\GroupExistsException;
                }

                //Move group and data files
                rename(System::GetDataPath() . "groups/$group_name", System::GetDataPath() . "groups/{$group_data->machine_name}");

                //Move users to new group directory
                rename(System::GetDataPath() . "users/$group_name", System::GetDataPath() . "users/{$group_data->machine_name}");
            }
        }
        else
        {
            throw new Exceptions\FileSystem\SystemGroupException;
        }
    }

    /**
     * Get a group object.
     * @param string $group_name
     * @return \Cms\Data\Group.
     * @throws \Cms\Exceptions\Group\GroupNotExistsException
     */
    public static function GetData($group_name)
    {
        $group_data_path = self::GetPath($group_name);

        if(file_exists($group_data_path))
        {
            $group_object = new Data\Group($group_name);

            $data = new Data($group_data_path);

            $group_data = $data->GetRow(0);
            $group_object->name = trim($group_data['name']);
            $group_object->description = trim($group_data['description']);

            return $group_object;
        }
        else
        {
            throw new Exceptions\Group\GroupNotExistsException;
        }
    }
    
    /**
     * Get a predefined guest group.
     * @staticvar \Cms\Data\Group $guest
     * @return \Cms\Data\Group
     */
    public static function GetGuestGroup()
    {
        static $guest;
        
        if(!is_object($guest))
        {
            $guest = new Data\Group('guest', 'Guest');
        }
        
        return $guest;
    }

    /**
     * Checks if a given user group exists.
     * @param string $group_name
     * @return bool
     */
    public static function Exists($group_name)
    {
        $group_path = self::GetPath($group_name);

        if(file_exists($group_path))
            return true;

        return false;
    }

    /**
     * Gets a list of existing groups on the system.
     * @return \Cms\Data\Group[].
     */
    public static function GetList()
    {
        $groups_directory = System::GetDataPath() . 'groups';
        
        $dir_handle = opendir($groups_directory);
        $groups = array();

        while (($group_directory = readdir($dir_handle)) !== false)
        {
            if(!is_dir($groups_directory . '/' . $group_directory))
                continue;

            //just check directories inside and skip the guest user group
            if (strcmp($group_directory, '.') != 0 && strcmp($group_directory, '..') != 0 && strcmp($group_directory, 'guest') != 0)
            {
                $group_data = self::GetData($group_directory);

                $groups[] = $group_data;
            }
        }

        return $groups;
    }
    
    /**
     * Get all permissions and its current values.
     * @staticvar array $permission_table
     * @param string $group_name
     * @return \Cms\Data\PermissionsList[]
     */
    public static function GetPermissions($group_name)
    {
        static $permission_table;

        if(!isset($permission_table[$group_name]))
        {
            if(!is_array($permission_table))
                $permission_table = array();
            
            $permissions_data_path = str_replace(
                '/data.php', 
                '/permissions.php', 
                self::GetPath($group_name)
            );  

            if(file_exists($permissions_data_path))
            {
                $data = new Data;
                $permission_table[$group_name] = $data->Parse($permissions_data_path);
            }
        }

        $permissions = array();
        
        $permissions[] = new Permissions\UserPermissions;
        $permissions[] = new Permissions\GroupPermissions;
        
        if($group_name == 'administrator')
        {
            foreach($permissions as &$permission_group)
            {
                $permission_group->SetAllPermissionsTrue();
            }
            
            return $permissions;
        }
        
        if(isset($permission_table[$group_name]))
        {
            foreach($permission_table[$group_name][0] as $field_name=>$field_value)
            {
                foreach($permissions as &$permission_group)
                {
                    if($permission_group->SetPermission($field_name, $field_value))
                    {
                        break;
                    }
                }
            }
        }
        
        return $permissions;
    }

    /**
     * Generates the data path for a group.
     * @param string $group_name
     * @return string
     */
    public static function GetPath($group_name)
    {
        return System::GetDataPath() . "groups/$group_name/data.php";
    }
}

?>
