<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

/**
 * Functions to handle users login and authentication.
 */
class Authentication
{
    /**
     * Login a user to the site if username and password
     * is correct on a form submit.
     * @return bool true on success or false on incorrect login.
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     * @throws \Cms\Exceptions\Users\InvalidPasswordException
     * @throws \Cms\Exceptions\Users\AwaitingApprovalException
     */
    public static function Login($username, $password)
    {
        $base_url = System::GetBaseUrl();

        //Remove the optional www for problems from www and non www links
        $logged_site = str_replace(array("http://", "https://", "www."), "", $_SESSION["logged"]["site"]);
        $base_url_parsed = str_replace(array("http://", "https://", "www."), "", $base_url);

        if($logged_site != $base_url_parsed)
        {
            $user_data = false;

            $email_validator = new Form\Validator\EmailValidator($username);
            
            if($email_validator->IsValid())
            {
                //This throws a user not exists exception if fails
                $user_data = Users::GetDataByEmail($username);
                $username = $user_data->username;
            }
            else
            {
                //This throws a user not exists exception if fails
                $user_data = Users::GetData($username);
            }
            
            if($user_data && crypt($password, $user_data->password) == $user_data->password)
            {
                $settings = System::GetSiteSettings();

                $groups_approval = unserialize($settings->Get("registration_groups_approval"));

                if(($settings->Get("registration_needs_approval") && $user_data->status == "0" && !$settings->Get("registration_can_select_group")) ||
                   ($settings->Get("registration_can_select_group") && $user_data->status == "0" && in_array($user_data->group, $groups_approval))
                )
                {
                    throw new Exceptions\Users\AwaitingApprovalException;
                }

                $_SESSION["logged"]["site"] = $base_url;
                $_SESSION["logged"]["username"] = $user_data->username;
                $_SESSION["logged"]["password"] = $user_data->password;
                $_SESSION["logged"]["group"] = $user_data->group;
                $_SESSION["logged"]["ip_address"] = $_SERVER["REMOTE_ADDR"];
                $_SESSION["logged"]["user_agent"] = $_SERVER["HTTP_USER_AGENT"];

                //Save last ip used
                $user_data->ip = $_SERVER["REMOTE_ADDR"];
                Users::Edit($username, $user_data);
            }
            else
            {
                $_SESSION["logged"]["site"] = false;
                
                throw new Exceptions\Users\InvalidPasswordException;
            }
        }
    }

    /**
     * Logs out the user from the system by clearing the needed session variables.
     */
    public static function Logout()
    {
        unset($_SESSION["logged"]);
    }
    
    /**
     * Checks if a user is logged in.
     * @staticvar \Cms\Data\User $user_data
     * @return bool true if user is logged or false if not.
     */
    public static function IsUserLogged()
    {
        static $user_data;
        $base_url = System::GetBaseUrl();

        if(!isset($_SESSION))
            return false;

        //To reduce file access
        if(!$user_data)
        {
            if(isset($_SESSION["logged"]["username"]))
            {
                if(Users::Exists($_SESSION["logged"]["username"]))
                    $user_data = Users::GetData($_SESSION["logged"]["username"]);
                else
                    $user_data = array();
            }
        }

        //Remove the optional www for problems from www and non www links
        $logged_site = str_replace(array("http://", "https://", "www."), "", $_SESSION["logged"]["site"]);
        $base_url_parsed = str_replace(array("http://", "https://", "www."), "", $base_url);

        if($logged_site == $base_url_parsed &&
                $user_data["password"] == $_SESSION["logged"]["password"] &&
                ($_SESSION["logged"]["user_agent"] == $_SERVER["HTTP_USER_AGENT"] ||
                ($_SERVER["HTTP_USER_AGENT"] == "Shockwave Flash" && isset($_FILES)) //Enable flash uploaders that send another agent
                )
        )
        {
            //If validation by ip is enabled check if ip the same to continue
            if(System::GetSiteSettings()->Get("validate_ip"))
            {
                if($_SESSION["logged"]["ip_address"] != $_SERVER["REMOTE_ADDR"])
                {
                    self::Logout();
                    return false;
                }
            }

            $_SESSION["logged"]["group"] = $user_data["group"];

            return true;
        }
        else
        {
            self::Logout();
            return false;
        }
    }

    /**
     * Checks if the administrator is logged in.
     * @return bool true if the admin is logged or false if not.
     */
    public static function IsAdminLogged()
    {
        if(self::GetGroup()->machine_name == "administrator")
        {
            return true;
        }

        return false;
    }
    
    /**
     * Get the current logged user.
     * @staticvar \Cms\Data\User $user
     * @return \Cms\Data\User
     */
    public static function GetUser()
    {
        static $user;

        if(self::IsUserLogged())
        {
            if(is_object($user))
            {
                if($user->username == 'guest')
                    unset($user);
            }

            if(!$user)
            {
                $user = Users::GetData($_SESSION['logged']['username']);
            }
        }
        else
        {
            $user = Users::GetGuestUser();
        }

        return $user;
    }

    /**
     * Get the group of the current logged user.
     * @staticvar \Cms\Data\Group $group
     * @return \Cms\Data\Group
     */
    public static function GetGroup()
    {
        static $group;

        if(self::IsUserLogged())
        {
            if(is_object($group))
            {
                if($group->group == 'guest')
                    unset($group);
            }

            if(!$group)
            {
                $group = Groups::GetData($_SESSION['logged']['group']);
            }
        }
        else
        {
            $group = Groups::GetGuestGroup();
        }

        return $group;
    }

    /**
     * Protects a page from guess access redirecting to an access denied page.
     * Used on pages where the administrator should be logged in or user with
     * proper permissions.
     * @see \Cms\Enumerations\Permissions
     * @param array|string
     */
    public static function ProtectPage($permissions = '')
    {
        if(self::IsAdminLogged())
        {
            return;
        }
        elseif(count($permissions) > 0)
        {
            if(self::GetGroup()->HasPermission($permissions))
            {
                return;
            }
        }

        System::SetHTTPStatus(Enumerations\HTTPStatusCode::UNAUTHORIZED);
        Uri::Go('access-denied');
    }
}

?>
