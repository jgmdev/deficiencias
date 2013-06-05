<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

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
        $page_file = self::GetPath($uri);
        $page_data = new Data\Page();
        
        if(file_exists($page_file))
        {
            $data = new Data($page_file);
            $data_row = $data->GetRow(0);
            
            $page_data->title = $data_row['title'];
            $page_data->content = $data_row['content'];
            $page_data->type = $data_row['type'];
        }
        else
        {   
            $page_data->title = t('Page not found');
            $page_data->content = t('The page you are visiting does not exists.');
            $page_data->type = Enumerations\PageType::HTML;
            $page_data->http_status_code = Enumerations\HTTPStatusCode::NOT_FOUND;
        }
        
        return $page_data;
    }
    
    public static function GetPath($uri)
    {
        return System::GetDataPath() . 'pages/' . Uri::TextToPath($uri) . '.php';
    }
}

?>
