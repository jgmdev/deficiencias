<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

use Cms\System;

/**
 * Description of groups
 *
 * @author jgm
 */
class Groups {
    
   /**
    * Adds a new group to the system.
    *
    * @param string $group_name The machine readable froup to create on the system.
    * @param array $fields An array with the needed fields to write to the group in the
    *               format array(name=>value, description=>value).
    *
    * @return string "true" string on success or error message on fail.
    */
    static function Add($group)
    {
       $group_data_path = generate_group_path($group_name);

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

    /**
    * Gets the value of a given permission.
    *
    * @param string $permission_name The machine name of the permission.
    * @param string $group_name The group we want to get permission value from.
    *
    * @return bool True if the group has the permissions or false.
    */
    function get_group_permission($permission_name, $group_name)
    {
       static $permission_table;

       if($group_name == "administrator")
       {
           return true;
       }

       if(!$permission_table)
       {
           $permissions_data_path = generate_group_path($group_name);
           $permissions_data_path = str_replace("/data.php", "/permissions.php", $permissions_data_path);  

           if(file_exists($permissions_data_path))
           { 
               $permission_table = data_parser($permissions_data_path);
           }
       }

       if(is_array($permission_table))
       {
          return trim($permission_table[0][$permission_name]);
       }

       return null;
    }

    /**
     * Gets the permission status of a given type for a users group.
     *
     * @param string $type The machine name of the type.
     * @param string $group_name The group we want to get permission value from.
     * @param string $username If passed also checks max posts amount hasnt been reached for the user.
     *
     * @return bool True if the group has the permissions or false.
     */
    function get_type_permission($type, $group_name, $username=false)
    {
       if(get_group_permission($type . "_type", $group_name))
       {
           if($username)
           {
               if(user_reached_max_posts($type, $username))
                   return false;
           }

           return true;
       }

       return false;
    }

    /**
     * Sets the value of a given permission.
     *
     * @param string $permission_name The machine name of the permission to set.
     * @param string $value The new value given to the permission.
     * @param string $group_name The name of the group the set the permission on.
     *
     * @return bool True on success or false on fail.
     */
    function set_group_permission($permission_name, $value, $group_name)
    {
       $permissions_data_path = generate_group_path($group_name);
       $permissions_data_path = str_replace("/data.php", "/permissions.php", $permissions_data_path);

       $permissions_data = array();

       if(file_exists($permissions_data_path))
       {
           $permissions_data = get_data(0, $permissions_data_path);
       }

       $permissions_data[$permission_name] = $value;

       return edit_data(0, $permissions_data, $permissions_data_path);
    }

    /**
     * Gets an array of existing permissions.
     * 
     * @param string $group The machine name of the group.
     *
     * @return array Array in the format permissions["group"] = array("machine_name"=>"Human Name").
     */
    static function get_permissions_array($group)
    {
       //Block Permissions
       $blocks["view_blocks"] = t("View");
       $blocks["add_blocks"] = t("Create");
       $blocks["edit_blocks"] = t("Edit");
       $blocks["delete_blocks"] = t("Delete");
       $blocks["return_code_blocks"] = t("Return Code");
       $blocks["input_format_blocks"] = t("Select input format");

       //Content Block Permissions
       $content_blocks["view_content_blocks"] = t("View");
       $content_blocks["add_content_blocks"] = t("Create");
       $content_blocks["edit_content_blocks"] = t("Edit");
       $content_blocks["delete_content_blocks"] = t("Delete");
       $content_blocks["edit_post_settings_content_blocks"] = t("Edit post settings");
       $content_blocks["return_code_content_blocks"] = t("Return Code");
       $content_blocks["input_format_content_blocks"] = t("Select input format");

       //Content Permissions
       $content["view_content"] = t("View");
       $content["add_content"] = t("Create");
       $content["edit_content"] = t("Edit");
       $content["delete_content"] = t("Delete");
       $content["select_type_content"] = t("Select type");
       $content["select_content_groups"] = t("Select groups");
       $content["add_edit_meta_content"] = t("Add/Edit Meta Tags");
       $content["input_format_content"] = t("Select input format");
       $content["manual_uri_content"] = t("Permit manually enter uri");
       $content["edit_all_user_content"] = t("Can edit all users content");

       //File permissions
       $files["view_files"] = t("View");
       $files["add_files"] = t("Create");
       $files["edit_files"] = t("Edit");
       $files["delete_files"] = t("Delete");

       //Image permissions
       $images["view_images"] = t("View");
       $images["add_images"] = t("Create");
       $images["edit_images"] = t("Edit");
       $images["delete_images"] = t("Delete");
       $images["edit_upload_width"] = t("Edit upload width");

       //Input formats permissions
       $input_formats["view_input_formats"] = t("View");
       $input_formats["add_input_formats"] = t("Create");
       $input_formats["edit_input_formats"] = t("Edit");
       $input_formats["delete_input_formats"] = t("Delete");

       //Content types access
       $types_list = get_types_list();
       $types_access = array();
       foreach($types_list as $machine_name=>$type_data)
       {
           $types_access[$machine_name . "_type"] = t($type_data["name"]);
       }

       //Types
       $types["view_types"] = t("View");
       $types["add_types"] = t("Create");
       $types["edit_types"] = t("Edit");
       $types["delete_types"] = t("Delete");

       //Categories
       $categories["view_categories"] = t("View");
       $categories["add_categories"] = t("Create");
       $categories["edit_categories"] = t("Edit");
       $categories["delete_categories"] = t("Delete");

       //Subcategories
       $subcategories["view_subcategories"] = t("View");
       $subcategories["add_subcategories"] = t("Create");
       $subcategories["edit_subcategories"] = t("Edit");
       $subcategories["delete_subcategories"] = t("Delete");

       //Menu Permissions
       $menus["view_menus"] = t("View");
       $menus["configure_menus"] = t("Configure");
       $menus["add_menus"] = t("Create");
       $menus["edit_menus"] = t("Edit");
       $menus["delete_menus"] = t("Delete");

       //Menu Item Permissions
       $menu_items["add_menu_items"] = t("Create");
       $menu_items["edit_menu_items"] = t("Edit");
       $menu_items["delete_menu_items"] = t("Delete");

       //User Permissions
       $users["view_users"] = t("View");
       $users["add_users"] = t("Create");
       $users["edit_users"] = t("Edit");
       $users["delete_users"] = t("Delete");

       //Group Permissions
       $groups["view_groups"] = t("View");
       $groups["add_groups"] = t("Create");
       $groups["edit_groups"] = t("Edit");
       $groups["delete_groups"] = t("Delete");

       //Site Settings
       $settings["edit_settings"] = t("Edit");

       //Theme
       $theme["select_theme"] = t("Select");

       //Languages
       $languages["view_languages"] = t("View");
       $languages["add_languages"] = t("Create");
       $languages["edit_languages"] = t("Edit");
       $languages["translate_languages"] = t("Translate");

       //Modules
       $modules["view_modules"] = t("View");
       $modules["install_modules"] = t("Install");
       $modules["uninstall_modules"] = t("Uninstall");
       $modules["upgrade_modules"] = t("Upgrade");


       //Group all permissions
       $permissions[t("Blocks")] = $blocks;
       $permissions[t("Content Blocks")] = $content_blocks;
       $permissions[t("Content")] = $content;
       $permissions[t("Content Types")] = $types;
       $permissions[t("Categories")] = $categories;
       $permissions[t("Files")] = $files;
       $permissions[t("Images")] = $images;
       $permissions[t("Input Formats")] = $input_formats;
       $permissions[t("Subcategories")] = $subcategories;
       $permissions[t("Menus")] = $menus;
       $permissions[t("Menu Items")] = $menu_items;
       $permissions[t("Users")] = $users;
       $permissions[t("Groups")] = $groups;
       $permissions[t("Site Settings")] = $settings;
       $permissions[t("Themes")] = $theme;
       $permissions[t("Types Access")] = $types_access;
       $permissions[t("Languages")] = $languages;
       $permissions[t("Modules")] = $modules;

       //Call set_group_permission hook before returning the permissions
       hook_module("hook_set_group_permission", $permissions, $group);

       ksort($permissions);

       return $permissions;
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
    * Generates the neccesary array for the form fields.
    *
    * @param array $selected The array of selected groups on the control.
    * 
    * @return array wich represent a series of fields that can
    * 		  be used when generating a form on a fieldset.
    */
    function generate_groups_fields_list($selected=null)
    {
       $fields = array();

       $groups_list = get_group_list();
       $groups_list[] = "guest";

       foreach($groups_list as $machine_name)
       {
           $group_data = get_group_data($machine_name);

           $groups[t($group_data["name"])] = $machine_name;

           $checked = false;
           if($selected)
           {
               foreach($selected as $value)
               {
                   if($value == $machine_name)
                   {
                       $checked = true;
                       break;
                   }
               }
           }

           $fields[] = array("type"=>"checkbox", "checked"=>$checked, "label"=>t($group_data["name"]), "name"=>"groups[]", "id"=>"groups", "value"=>$machine_name);
       }

       return $fields;
    }

   /**
    * Generates the data path for a group.
    *
    * @param string $group_name The group to translate to a valid user data path.
    */
    static function GetPath($group_name)
    {
       $group_data_path = System::GetDataPath() . "groups/$group_name/data.php";

       return $group_data_path;
    }
}

?>
