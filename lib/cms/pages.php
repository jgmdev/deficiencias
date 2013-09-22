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
        if($uri == '')
            $uri = System::GetHomePage();

        $page_file = self::GetPath($uri);
        $page_data = new Data\Page($uri);

        if(file_exists($page_file))
        {
            $data = new Data($page_file);
            $data_row = $data->GetRow(0, $page_data);
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

    public static function GetPath($uri)
    {
        $uri = str_replace('/', '-', $uri);

        return System::GetDataPath() . 'pages/' . Uri::TextToUri($uri) . '.php';
    }
}

?>
