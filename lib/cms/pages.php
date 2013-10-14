<?php
/**
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

use Cms\Enumerations\Permissions;

/**
 * Functions to handle pages
 */
class Pages
{
    /**
     * Disable constructor.
     */
    private function __construct() {}

    /**
     * Loads a page if exists or creates a page not found object if not.
     * @param string $uri Path of the page to load, for example: my/section
     * @return \Cms\Data\Page
     */
    public static function Load($uri)
    {
        if($uri == '')
            $uri = System::GetHomePage();

        $page_file = self::GetPath($uri);
        $page_data = new Data\Page($uri);

        if(file_exists($page_file))
        {
            $data = new Data($page_file);
            $data->GetRow(0, $page_data);
            
            if(!is_array($page_data->groups))
            {
                if(is_string($page_data->groups))
                    $page_data->groups = unserialize($page_data->groups);
                else
                    $page_data->groups = array();
            }
            
            if(!is_array($page_data->permissions))
            {
                if(is_string($page_data->permissions))
                    $page_data->permissions = unserialize($page_data->permissions);
                else
                    $page_data->permissions = array();
            }
        }
        else
        {
            $page_data->title = t('Page not found');
            $page_data->content = t('The page you are visiting does not exists.');
            $page_data->rendering_mode = Enumerations\PageRenderingMode::NORMAL;
            $page_data->http_status_code = Enumerations\HTTPStatusCode::NOT_FOUND;
        }

        return $page_data;
    }
    
    /**
     * Checks if a given page exists.
     * @param string $uri
     * @return bool
     */
    public static function Exists($uri)
    {
        $page_path = self::GetPath($uri);

        if(file_exists($page_path))
            return true;

        return false;
    }
    
    /**
     * Checks if a given uri is from a system page.
     * @param type $uri
     * @return boolean
     */
    public static function IsSystem($uri)
    {
        $system_page_uri = 'system/' . Uri::TextToUri($uri, true) . '.php';
        
        if(file_exists($system_page_uri))
            return true;
        
        return false;
    }
    
    /**
     * Generate a list of page groups useful for creating a control center
     * administration page.
     * @return array
     */
    public static function GetAdminPageGroups()
    {
        $page_groups = new Data\PagesGroupList;
        
        // Users
        $users_view = new Data\Page('admin/users');
        $users_view->title = t('View');
        $users_view->description = t('View the existing users on the system.');
        $users_view->AddPermission(Permissions\Users::VIEW);
                
        $users_add = new Data\Page('admin/users/add');
        $users_add->title = t('Add');
        $users_add->description = t('Create new user account.');
        $users_add->AddPermission(Permissions\Users::CREATE);
        
        $page_groups->AddGroup(t('Users'), array(
           $users_view, $users_add 
        ));
        
        // Groups
        $groups_view = new Data\Page('admin/groups');
        $groups_view->title = t('View');
        $groups_view->description = t('View the existing groups on the system.');
        $groups_view->AddPermission(Permissions\Groups::VIEW);
                
        $groups_add = new Data\Page('admin/groups/add');
        $groups_add->title = t('Add');
        $groups_add->description = t('Create new user account.');
        $groups_add->AddPermission(Permissions\Groups::CREATE);
        
        $page_groups->AddGroup(t('Groups'), array(
           $groups_view, $groups_add 
        ));
        
        // Send Control Center page generation signal
        $signal_data = new Signals\SignalData;
        $signal_data->Add('page_groups', $page_groups);
        
        Signals\SignalHandler::Send(
            Enumerations\Signals\Gui::GENERATE_CONTROL_CENTER, 
            $signal_data
        );
        
        return $page_groups->GetPermittedGroups();
    }

    /**
     * Get file path of a given uri. It can return the path to a system
     * page or regular site page.
     * @param string $uri
     * @return string
     */
    public static function GetPath($uri)
    {
        $system_page_uri = 'system/' . Uri::TextToUri($uri, true) . '.php';
        
        if(file_exists($system_page_uri))
            return $system_page_uri;
        
        $uri = str_replace('/', '-', $uri);

        return System::GetDataPath() . 'pages/' . Uri::TextToUri($uri) . '.php';
    }
}

?>
