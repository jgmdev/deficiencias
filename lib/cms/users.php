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
       
       $user_data = new Data($path . "data.php");
       $user_data->Write($user);
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

    public static function Edit($username, $user_data)
    {
        
    }

    /**
     * 
     * @param type $username
     * @return \Cms\Data\User
     */
    public static function GetData($username)
    {
        
    }

    public static function GetDataByEmail($email)
    {
        
    }
    
    public static function Exists($username)
    {
        
    }

    public static function ResetPasswordByUsername($username)
    {
        
    }

    public static function ResetPasswordByEmail($email)
    {
        
    }

    public static function GeneratePassword()
    {
        $password = str_replace(array("\$", ".", "/"), "",crypt(uniqid(rand(),1)));

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