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
class Groups 
{
    /**
     * Disable constructor
     */
    private function __construct() {}
    
   /**
    * Adds a new group to the system.
    * @param \Cms\Data\Group $group
    */
    public static function Add(\Cms\Data\Group $group)
    {
       $group_name = $group->machine_name;
       $group_data_path = self::GetPath($group_name);

       //Check if group file already exist
       if(!file_exists($group_data_path))
       {
           //Create group directory
           FileSystem::MakeDir(System::GetDataPath() . "groups/$group_name", 0755, true);
           
           $group_data = new Data($group_data_path);
           $group_data->Write($group);

           //Create user group directory
           FileSystem::MakeDir(System::GetDataPath() . "users/$group_name", 0755, true);
       }
       else
       {
           throw new Exceptions\FileSystem\GroupExistsException(t("The group you are trying to add already exists."));
       }
    }

    /**
    * Deletes an existing group.
    *
    * @param string $group_name The machine readable group to delete.
    *
    * @return string "true" string on success or error message on fail.
    */
    public static function Delete($group_name)
    {
       $group_data_path = self::GetPath($group_name);

       //Check if group is not from system
       if($group_name != "administrator" && $group_name != "regular" && $group_name != "guest")
       {
           //Delete group files
           if(!FileSystem::RecursiveRemoveDir(System::GetDataPath() . "groups/$group_name"))
           {
               throw new Exceptions\FileSystem\WriteFileException;
           }

           //Move existing users from deleted group to regular group
           FileSystem::RecursiveMoveDir(System::GetDataPath() . "users/$group_name", System::GetDataPath() . "users/regular");

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
    *
    * @param string $group_name The machine readable group.
    *
    * @return string "true" string on success or error message on fail.
    */
    static function Edit($name, $group_data)
    {
       $group_data_path = self::GetPath($group_name);

       if(!edit_data(0, $new_data, $group_data_path))
       {
           return error_message("write_error_data");
       }

       //Check if group is not from system
       if($group_name != "administrator" && $group_name != "regular" && $group_name != "guest")
       {
           //If a new machine readable group name is passed make appropriate changes.
           if($new_name != "" && $new_name != $group_name)
           {
               //If the new group name already exist skip
               if(file_exists(data_directory() . "groups/$new_name"))
               {
                   return error_message("group_exist");
               }

               //Move group and data files
               rename(data_directory() . "groups/$group_name", data_directory() . "groups/$new_name");

               //Move users to new group directory
               rename(data_directory() . "users/$group_name", data_directory() . "users/$new_name");
           }
       }
       else
       {
           return error_message("edit_system_group");
       }

       return "true";
    }

    /**
    * Get an array with data of a specific group.
    *
    * @param string $group_name The group.
    *
    * @return array An array with all the rows and fields of the group.
    */
    static function GetData($group_data)
    {
       $group_data_path = generate_group_path($group_name);

       $group_data = data_parser($group_data_path);

       if($group_data)
       {
           $group_data[0]["name"] = trim($group_data[0]["name"]);
           $group_data[0]["description"] = trim($group_data[0]["description"]);
           return $group_data[0];
       }
       else
       {
           return null;
       }
    }

    /**
    * Gets a list of existing groups on the system.
    *
    * @return array An array of groups in the format array(name=>"group directory name").
    */
    public static function GetList()
    {
       $dir_handle = opendir(System::GetDataPath() . "groups");
       $groups = array();

       while(($group_directory = readdir($dir_handle)) !== false)
       {
           //just check directories inside and skip the guest user group
           if(strcmp($group_directory, ".") != 0 && strcmp($group_directory, "..") != 0 && strcmp($group_directory, "guest") != 0)
           {
               $group_data = self::GetData($group_directory);

               $groups[] = $group_data;
           }
       }

       return $groups;
    }

   /**
    * Generates the data path for a group.
    *
    * @param string $group_name The group to translate to a valid user data path.
    */
    public static function GetPath($group_name)
    {
       return System::GetDataPath() . "groups/$group_name/data.php";
    }
}

?>
