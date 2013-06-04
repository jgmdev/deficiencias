<?php 
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

use Cms\System;

/**
 * Class to handle users
 */
class Users
{
    /**
     * Adds a new user into the system.
     * @param \Cms\Data\User $user
     */
    public static function Add($user)
    {
       $path = self::GetPath($user->username, $user->group);
       
       if(file_exists($path))
           throw new Exception\Users\UserExistsException;
       
       FileSystem::MakeDir($path, 0755, true);
       
       $row = array();
       $row[0] = (array) $user;
       
       $user_data = new Data($path . "data.php");
       $user_data->Write($row);
    }

    /**
     * Deletes a user account from the system.
     * @param string $username
     * @throws Exception\Users\UserNotExistsException
     */
    public static function Delete($username)
    {
        $path = self::GetPath($user->username, $user->group);
        
        if(!file_exists($path))
           throw new Exception\Users\UserNotExistsException;
        
        $user_data = self::GetData($username);
        
        FileSystem::RecursiveRemoveDir($path);
        
        //Remove old data/users/group_name/X/XX if empty
		rmdir(System::GetDataPath() . "users/{$user_data->group}/" . substr($username, 0, 1) . '/' . substr($username, 0, 2));

		//Remove old data/users/group_name/X if empty
		rmdir(System::GetDataPath() . "users/{$user_data->group}/" . substr($username, 0, 1));
    }

    /**
     * Edits a user.
     * @param string $username
     * @param \Cms\Data\User $user_data
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     */
    public static function Edit($username, $user_data)
    {
        $username = strtolower($username);
        $user_exist = self::Exists($username);

        if($user_exist)
        {
            $user_data_path = $user_exist["path"];
            
            $data = new Data($user_data_path . "data.php");
            $data->EditRow(0, $user_data);

            //Change user group
            if($user_data->group != $user_exist["group"])
            {
                $new_path = self::GetPath($username, $user_data->group);

                //Make new user path
                FileSystem::MakeDir($new_path, 0755, true);

                //Move user data to new group
                FileSystem::RecursiveMoveDir($user_data_path, $new_path);

                //Remove old main user directory
                FileSystem::RecursiveRemoveDir($user_data_path);

                //Remove old data/users/group_name/X/XX if empty
                rmdir(System::GetDataPath() . "users/{$user_exist['group']}/" . substr($username, 0, 1) . "/" . substr($username, 0, 2));

                //Remove old data/users/group_name/X if empty
                rmdir(System::GetDataPath() . "users/{$user_exist['group']}/" . substr($username, 0, 1));
            }
        }
        else
        {
            throw new Exceptions\Users\UserNotExistsException;
        }
    }

    /**
     * Gets the data of a user
     * @param type $username
     * @return \Cms\Data\User
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     */
    public static function GetData($username)
    {
        $username = strtolower($username);
        $user_exist = self::Exists($username);

        if($user_exist)
        {
            $user_data_path = $user_exist['path'];

            $data = new Data($user_data_path . 'data.php');
            $user_data = $data->GetRow(0);
            $user_object = new Data\User();
            
            $user_object->username = $user_data['username'];
            $user_object->fullname = $user_data['fullname'];
            $user_object->group = $user_exist['group'];
            $user_object->password = $user_data['password'];
            $user_object->birth_date = $user_data['birth_date'];
            $user_object->gender = $user_data['gender'];
            $user_object->registration_date = $user_data['registration_date'];
            $user_object->status = $user_data['status'];
            $user_object->picture = $user_data['picture'];

            return $user_object;
        }
        else
        {
            throw new Exceptions\Users\UserNotExistsException;
        }
    }

    public static function GetDataByEmail($email)
    {
        
    }
    
    /**
     * Checks if a user exists.
     * @param string $username The username to check.
     * @return boolean
     */
    public static function Exists($username)
    {
        $username = strtolower($username);
        $dir_handle = opendir(System::GetDataPath() . "users");

        if(!is_bool($dir_handle))
        {
            while(($group_directory = readdir($dir_handle)) !== false)
            {
                //just check directories inside
                if(strcmp($group_directory, ".") != 0 && strcmp($group_directory, "..") != 0)
                {
                    $user_data_path = self::GetPath($username, $group_directory);

                    if(file_exists($user_data_path))
                    {
                        return array("path"=>$user_data_path, "group"=>$group_directory);
                    }
                }
            }
         }

        return false;
    }

    public static function ResetPasswordByUsername($username)
    {
        
    }

    public static function ResetPasswordByEmail($email)
    {
        
    }

    public static function GeneratePassword()
    {
        $password = str_replace(array("\$", ".", "/"), "", crypt(uniqid(rand(),1)));

        if(strlen($password) > 10)
        {
            $password = substr($password, 0, 10);
        }

        return $password;
    }

    public static function SendResetPasswordNotification($username, $user_data, $password)
    {
        
    }
    
    public static function GetPath($username, $group)
    {
        $username = strtolower($username);

        //substitute the data page path with the data users path
        return System::GetDataPath() . "users/$group/{$username{0}}/{$username{0}}{$username{1}}/";
    }
}

?>