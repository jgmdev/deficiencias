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
    function Add($user)
    {
       
    }

    function Delete($username)
    {
        
    }

    function Edit($username, $user_data)
    {
        
    }

    function GetData($username)
    {
        
    }

    function GetDataByEmail($email)
    {
        
    }
    
    function Exists($username)
    {
        
    }

    function ResetPasswordByUsername($username)
    {
        
    }

    function ResetPasswordByEmail($email)
    {
        
    }

    function GeneratePassword()
    {
        $password = str_replace(array("\$", ".", "/"), "",crypt(uniqid(rand(),1)));

        if(strlen($password) > 10)
        {
            $password = substr($password, 0, 10);
        }

        return $password;
    }

    function SendResetPasswordNotification($username, $user_data, $password)
    {
        
    }
}

?>