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
     * Disable constructor
     */
    private function __construct(){}

    /**
     * Adds a new user into the system.
     * @param \Cms\Data\User $user
     * @throws \Cms\Exception\Users\UserExistsException
     */
    public static function Add(\Cms\Data\User $user)
    {
        $user->username = strtolower($user->username);
        
        $path = self::GetPath($user->username, $user->group);

        if(file_exists($path))
            throw new Exception\Users\UserExistsException;

        FileSystem::MakeDir($path, 0755, true);
        
        $user->password = crypt($user->password);
        
        if(!$user->registration_date)
            $user->registration_date = time();

        $user_data = new Data($path . 'data.php');
        $user_data->AddRow($user);

        $db = System::GetRelationalDatabase();
        
        if($db->TableExists('users'))
        {
            $insert = new DBAL\Query\Insert('users');
            $insert->Insert('username', $user->username, Enumerations\FieldType::TEXT)
                ->Insert('fullname', $user->fullname, Enumerations\FieldType::TEXT)
                ->Insert('email', $user->email, Enumerations\FieldType::TEXT)
                ->Insert('register_date', $user->registration_date, Enumerations\FieldType::INTEGER)
                ->Insert('user_group', $user->group, Enumerations\FieldType::TEXT)
                ->Insert('picture', $user->picture, Enumerations\FieldType::TEXT)
                ->Insert('ip', $user->ip, Enumerations\FieldType::TEXT)
                ->Insert('gender', $user->gender, Enumerations\FieldType::TEXT)
                ->Insert('birth_date', $user->birth_date, Enumerations\FieldType::INTEGER)
                ->Insert('status', $user->status, Enumerations\FieldType::TEXT)
            ;

            $db->Insert($insert);
        }
    }

    /**
     * Deletes a user account from the system.
     * @param string $username
     * @throws \Cms\Exception\Users\UserNotExistsException
     */
    public static function Delete($username)
    {
        $username = strtolower($username);
        
        $user_exists = self::Exists($username);

        if(!is_array($user_exists))
            throw new Exception\Users\UserNotExistsException;

        $path = self::GetPath($username, $user_exists['group']);

        FileSystem::RecursiveRemoveDir($path);

        //Remove old data/users/group_name/X/XX if empty
        rmdir(System::GetDataPath() . "users/{$user_exists['group']}/" . substr($username, 0, 1) . '/' . substr($username, 0, 2));

        //Remove old data/users/group_name/X if empty
        rmdir(System::GetDataPath() . "users/{$user_exists['group']}/" . substr($username, 0, 1));
        
        $db = System::GetRelationalDatabase();
        
        if($db->TableExists('users'))
        {
            $delete = new DBAL\Query\Delete('users');
            $delete->WhereEqual('username', $username, Enumerations\FieldType::TEXT);

            $db->Delete($delete);
        }
    }

    /**
     * Edits a user.
     * @param string $username
     * @param \Cms\Data\User $user_data
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     * @throws \Cms\Exceptions\FileSystem\WriteFileException
     * @throws \Cms\Exceptions\FileSystem\InvalidFileException
     */
    public static function Edit($username, \Cms\Data\User $user_data)
    {
        $username = strtolower($username);
        $user_exist = self::Exists($username);

        if($user_exist)
        {
            $user_data_path = $user_exist['path'];

            $data = new Data($user_data_path . 'data.php');
            $data->EditRow(0, $user_data);

            //Change user group
            if($user_data->group != $user_exist['group'])
            {
                $new_path = self::GetPath($username, $user_data->group);

                //Make new user path
                FileSystem::MakeDir($new_path, 0755, true);

                //Move user data to new group
                FileSystem::RecursiveMoveDir($user_data_path, $new_path);

                //Remove old main user directory
                FileSystem::RecursiveRemoveDir($user_data_path);

                //Remove old data/users/group_name/X/XX if empty
                rmdir(System::GetDataPath() . "users/{$user_exist['group']}/" . substr($username, 0, 1) . '/' . substr($username, 0, 2));

                //Remove old data/users/group_name/X if empty
                rmdir(System::GetDataPath() . "users/{$user_exist['group']}/" . substr($username, 0, 1));
            }
            
            $db = System::GetRelationalDatabase();
        
            if($db->TableExists('users'))
            {   
                $update = new DBAL\Query\Update('users');
                $update->Update('email', $user_data->email, Enumerations\FieldType::TEXT)
                    ->Update('fullname', $user_data->fullname, Enumerations\FieldType::TEXT)
                    ->Update('register_date', $user_data->registration_date, Enumerations\FieldType::INTEGER)
                    ->Update('user_group', $user_data->group, Enumerations\FieldType::TEXT)
                    ->Update('picture', $user_data->picture, Enumerations\FieldType::TEXT)
                    ->Update('ip', $user_data->ip, Enumerations\FieldType::TEXT)
                    ->Update('gender', $user_data->gender, Enumerations\FieldType::TEXT)
                    ->Update('birth_date', $user_data->birth_date, Enumerations\FieldType::INTEGER)
                    ->Update('status', $user_data->status, Enumerations\FieldType::TEXT)
                    ->WhereEqual('username', $username, Enumerations\FieldType::TEXT)
                ;

                $db->Update($update);
            }
        }
        else
        {
            throw new Exceptions\Users\UserNotExistsException;
        }
    }

    /**
     * Gets the data of a user
     * @param string $username
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
            $user_object = new Data\User();
            $user_object->username = $username;
            $data->GetRow(0, $user_object);
            $user_object->group = $user_exist['group'];

            return $user_object;
        }
        else
        {
            throw new Exceptions\Users\UserNotExistsException;
        }
    }

    /**
     * Gets the data of a user by its email.
     * @param string $email
     * @return \Cms\Data\User
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     */
    public static function GetDataByEmail($email)
    {
        $db = System::GetRelationalDatabase();

        if($db->TableExists('users'))
        {
            $select = new DBAL\Query\Select('users');

            $select->Select('username')
                    ->WhereEqual('email', $email, Enumerations\FieldType::TEXT)
            ;

            if($db->Select($select))
            {
                if($data = $db->FetchArray())
                    return self::GetData($data['username']);
            }
        }

        throw new Exceptions\Users\UserNotExistsException;
    }

    /**
     * Get a predefined guest user.
     * @staticvar \Cms\Data\User $guest
     * @return \Cms\Data\User
     */
    public static function GetGuestUser()
    {
        static $guest;

        if(!is_object($guest))
        {
            $guest = new Data\User;
            $guest->username = 'guest';
            $guest->group = 'guest';
        }

        return $guest;
    }

    /**
     * Checks if a user exists.
     * @param string $username
     * @return bool
     */
    public static function Exists($username)
    {
        $username = strtolower($username);
        $dir_handle = opendir(System::GetDataPath() . 'users');

        if(!is_bool($dir_handle))
        {
            while(($group_directory = readdir($dir_handle)) !== false)
            {
                //just check directories inside
                if(strcmp($group_directory, '.') != 0 && strcmp($group_directory, '..') != 0)
                {
                    $user_data_path = self::GetPath($username, $group_directory);

                    if(file_exists($user_data_path))
                    {
                        return array('path' => $user_data_path, 'group' => $group_directory);
                    }
                }
            }
        }

        return false;
    }
    
    /**
     * Checks if a given user email is already take.
     * @param string $email
     * @return bool
     */
    public static function EmailTaken($email)
    {
        try
        {
            $user_data = self::GetDataByEmail($email);
        }
        catch(Exceptions\Users\UserNotExistsException $e)
        {
            return false;
        }
        
        return true;
    }

    /**
     * 
     * @param type $username
     * @return string $username
     * @throws \Cms\Exceptions\Users\UserNotExistsException
     */
    public static function ResetPasswordByUsername($username)
    {
        $username = strtolower($username);
        $password = self::GeneratePassword();
        $user_data = self::GetData($username);
        $user_data->password = crypt($password);

        self::Edit($username, $user_data);

        self::SendResetPasswordNotification($user_data, $password);
    }

    public static function ResetPasswordByEmail($email)
    {
        $db = System::GetRelationalDatabase();

        if($db->TableExists('users'))
        {
            $user_select = new DBAL\Query\Select('users');
            $user_select->Select('username')
                ->WhereEqual('email', $email, Enumerations\FieldType::TEXT)
            ;
            
            if($db->Select($user_select))
            {
                $data = $db->FetchArray();
                
                if(isset($data['username']))
                {
                    if($data['username'] != '')
                    {
                        $password = self::GeneratePassword();
                        $username = $data['username'];
                        $user_data = self::GetData($username);
                        $user_data->password = crypt($password);

                        self::Edit($username, $user_data);

                        self::SendResetPasswordNotification($user_data, $password);

                        return;
                    }
                }
            }
        }
        
        throw new Exceptions\Users\UserNotExistsException;
    }

    public static function GeneratePassword()
    {
        $password = str_replace(
            array('$', '.', '/'), 
            '', 
            crypt(uniqid(rand(), true))
        );

        if(strlen($password) > 10)
        {
            $password = substr($password, 0, 10);
        }

        return $password;
    }

    /**
     * 
     * @param \Cms\Data\User $user_data
     * @param string $password
     * @return boolean
     */
    public static function SendResetPasswordNotification(\Cms\Data\User $user_data, $password)
    {
        $username = $user_data->username;
        $to[$user_data->fullname] = $user_data->email;
        $subject = t('Your password has been reset.');

        $url = Uri::GetUrl('login');

        $message = t('Hi') . ' ' . $user_data->fullname . '<br /><br />';
        $message .= t('Your current username is:') . ' <b>' . $username . '</b><br />';
        $message .= t('The new password for your account is:') . ' <b>' . $password . '</b><br />';
        $message .= t('Is recommended that you log in and change the password as soon as possible.') . '<br />';
        $message .= t('To log in access the following url:') . ' <a href="'.$url.'">' . $url . '</a>';

        return Mail::Send($to, $subject, $message);
    }
    
    /**
     * Function that generates the content of user page and sends a signal
     * for modules to be able to modify user page content.
     */
    function GenerateUserPage()
    {
        $tabs[t('Edit My Account')] = array(
            'uri' => 'account/profile'
        );

        if(System::GetSiteSettings()->Get('user_profiles'))
        {
            $tabs[t('View My Profile')] = array(
                'uri' => 'user/' . Authentication::GetUser()->username
            );
        }

        if(Authentication::GetGroup()->HasPermission(Enumerations\Permissions\Groups::CREATE))
        {
            $tabs[t('My Content')] = array('uri' => 'account/content');
        }

        $content = '';

        if(Authentication::IsAdminLogged())
        {
            $tabs[t('Control Center')] = array('uri' => 'admin');

            $content = t('Welcome Administrator!') . '<br /><br />' . 
                t('Now that you are logged in you can start modifying the website as you need.')
            ;
        }
        else
        {
            $content = t('Welcome') . ' ' . Authentication::GetUser()->username . '!' . 
                '<br /><br />' . 
                t('Now that you are logged in you can enjoy the privileges of registered users on') . 
                ' ' . str_replace(array("http://", "https://"), "", System::GetBaseUrl()) . '.'
            ;
        }
        
        // Send user page generation signal
        $signal_data = new Signals\SignalData();
        $signal_data->content = $content;
        $signal_data->tabs = $tabs;
        
        Signals\SignalHandler::Send(Enumerations\Signals\User::GENERATE_PAGE, $signal_data);

        // Add tabs to user page
        foreach($tabs as $title => $data)
        {
            if(!isset($data['arguments']))
            {
                Theme::AddTab($title, $data['uri'], null, $data['row'] ? $data['row'] : 0);
            }
            else
            {
                Theme::AddTab($title, $data['uri'], $data['arguments'], $data['row'] ? $data['row'] : 0);
            }
        }

        Theme::AddTab(t('Logout'), 'logout');

        return $content;
    }

    /**
     * Get the path of a given user files by $username and $group.
     * @param string $username
     * @param string $group
     * @return string
     */
    public static function GetPath($username, $group)
    {
        $username = strtolower($username);

        return System::GetDataPath() . "users/$group/{$username{0}}/" .
            "{$username{0}}{$username{1}}/" .
            "$username/"
        ;
    }
}

?>