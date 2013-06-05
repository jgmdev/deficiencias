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
    static function Add($group)
    {
       $group_data_path = self::GetPath($group->machine_name);

       //Check if group file already exist
       if(!file_exists($group_data_path))
       {
           //Create group directory
           make_directory(data_directory() . "groups/$group_name", 0755, true);

           if(!add_data($fields, $group_data_path))
           {
               return error_message("write_error_data");
           }

           //Create user group directory
           make_directory(data_directory() . "users/$group_name", 0755, true);
       }
       else
       {
           //if file exist then group exist so return error message
           return error_message("group_exist");
       }

       return "true";
    }

    /**
    * Deletes an existing group.
    *
    * @param string $group_name The machine readable group to delete.
    *
    * @return string "true" string on success or error message on fail.
    */
    static function Delete($group)
    {
       $group_data_path = generate_user_path($group_name);

       //Check if group is not from system
       if($group_name != "administrator" && $group_name != "regular" && $group_name != "guest")
       {
           //Delete group files
           if(!recursive_remove_directory(data_directory() . "groups/$group_name"))
           {
               return error_message("write_error_data");
           }

           //Move existing users from deleted group to regular group
           recursive_move_directory(data_directory() . "users/$group_name", data_directory() . "users/regular");

           //Delete users group directory
           recursive_remove_directory(data_directory() . "users/$group_name");
       }
       else
       {
           //This is a system group and can not be deleted
           return error_message("delete_system_group");
       }

       return "true";
    }

    /**
    * Edits or changes the data of an existing group.
    *
    * @param string $group_name The machine readable group.
    * @param array $new_data An array of the fields that will substitue the old values.
    * @param string $new_name The new machine readable name.
    *
    * @return string "true" string on success or error message on fail.
    */
    static function Edit($name, $group_data)
    {
       $group_data_path = generate_group_path($group_name);

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

    static function GetPermissions()
    {
       
    }

    /**
    * Gets a list of existing groups on the system.
    *
    * @return array An array of groups in the format array(name=>"group directory name").
    */
    function get_group_list()
    {
       $dir_handle = opendir(data_directory() . "groups");
       $groups = array();


       while(($group_directory = readdir($dir_handle)) !== false)
       {
           //just check directories inside and skip the guest user group
           if(strcmp($group_directory, ".") != 0 && strcmp($group_directory, "..") != 0 && strcmp($group_directory, "guest") != 0)
           {
               $group_data = get_group_data($group_directory);

               $groups[$group_data["name"]] = $group_directory;
           }
       }

       return $groups;
    }

   /**
    * Generates the data path for a group.
    *
    * @param string $group_name The group to translate to a valid user data path.
    */
    static function GetPath($group_name)
    {
       return System::GetDataPath() . "groups/$group_name/data.php";
    }
}

?>
