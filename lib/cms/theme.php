<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

/**
 * Functions to handle theming
 */
class Theme 
{
    /**
     * List of css files to include on a rendered page.
     * @var array 
     */
    private static $styles;
    
    /**
     * List of javascript files to include on a rendered page.
     * @var array 
     */
    private static $scripts;
    
    /**
     * List of tabs to display on a rendered page.
     * @var array 
     */
    private static $tabs;
    
    /**
     * Disable constructor
     */
    private function __construct() {}
    
    /**
     * Adds a css file to be added when rendering a page.
     * @param string $path
     */
    public static function AddStyle($path)
    {
        self::$styles[$path] = Uri::GetUrl($path);
    }
    
    /**
     * Adds a javascript file to be added when rendering a page.
     * @param string $path
     */
    public static function AddScript($path)
    {
        self::$scripts[$path] = Uri::GetUrl($path);
    }
    
    /**
     * Adds a button/tab to be displayed when rendering the page.
     * @param string $caption
     * @param string $uri
     * @param array $arguments
     * @param int $row
     */
    public static function AddTab($caption, $uri, $arguments = null, $row=0)
    {
        self::$tabs[$row][$caption] = array("uri"=>$uri, "arguments"=>$arguments);
    }

    /**
     * Adds a message to be displayed when rendering the page.
     * @param string $message
     * @param string $type @see \Cms\Enumerations\MessageType
     */
    public static function AddMessage($message, $type = "normal")
    {
        $_SESSION["messages"][] = array("text"=>$message, "type"=>$type);
    }
    
    /**
     * Themes the content of a page using a template file.
     * @param \Cms\Data\Page $page
     * @return type
     */
    public static function ThemeContent($page)
    {
        $content  = Utilities::PHPEval($page->content);
        
        if($page->rendering_mode && $page->rendering_mode != Enumerations\PageRenderingMode::NORMAL)
        {
            return $content;
        }
        
        $formatted_content = '';
        
        ob_start();
            include(self::ContentTemplate($page->uri, $page->type));

            $formatted_content .= ob_get_contents();
        ob_end_clean();

        return $formatted_content;
    }

    /**
     * Generate the html code to insert css files on rendered pages.
     * @return string Html code for the head section of a document.
     */
    function GetStylesHTML()
    {
        $styles_code = '';
        
        if(count(self::$styles) > 0)
        {
            foreach(self::$styles as $file)
            {
                $styles_code .= "<link href=\"$file\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
            }
        }

        return $styles_code;
    }

    /**
     * Generate the html code to javascript files on rendered pages.
     * @return string Html code for the head section of document.
     */
    function GetScriptsHTML()
    {
        $scripts_code = '';
        
        if(count(self::$scripts) > 0)
        {
            foreach(self::$scripts as $file)
            {
                $scripts_code .= "<script type=\"text/javascript\" src=\"$file\"></script>\n";
            }
        }
        
        return $scripts_code;
    }
    
    /**
     * Generates the meta tags html for a rendered page.
     * @param \Cms\Data\Page $page
     */
    function GetMetaTagsHTML($page)
    {
        $meta_tags = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
        $meta_tags .= '<meta name="generator" content="Cms Framework" />'."\n";
        
        if($page->description)
        {
            $meta_tags .= '<meta name="description" content="'.$page->description.'" />'."\n";
        }

        if($page->keywords)
        {
            $meta_tags .= '<meta name="keywords" content="'.$page->keywords.'" />'."\n";
        }
        
        return $meta_tags;
    }

    /**
     * Generate the html code for the tabs.
     * @return Html code ready to render or empty string.
     */
    public static function GetTabsHTML()
    {
        $tabs = '';

        if(count(self::$tabs) > 0)
        {
            foreach(self::$tabs as $position=>$fields)
            {
                $tabs .= "<ul class=\"tabs tabs-$position\">\n";

                $total_tabs = count($fields);
                $index = 0;

                if(is_array($fields))
                {
                    foreach($fields as $name=>$uri)
                    {
                        $list_class = '';
                        if($index == 0)
                        {
                            $list_class = ' class="first" ';
                        }
                        else if($index+1 == $total_tabs)
                        {
                            $list_class = ' class="last" ';
                        }

                        $url = Uri::GetUrl($uri['uri'], $uri['arguments']);

                        if($uri['uri'] == Uri::GetCurrent())
                        {
                            $tabs .= "\t<li{$list_class}><span><a class=\"selected\" href=\"$url\">$name</a></span></li>\n";
                        }
                        else
                        {
                            $tabs .= "\t<li{$list_class}><span><a href=\"$url\">$name</a></span></li>\n";
                        }
                    }
                }

                $tabs .= '</ul>'."\n";

                $tabs .= '<div class="clear tabs-clear"></div>'."\n";
            }
        }

        return $tabs;
    }

    /**
     * Generates the html code for the messages.
     *
     * @return string html code ready to render or empty string.
     */
    public static function GetMessagesHTML()
    {
        if(isset($_SESSION['messages']))
        {
            $messages_array = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }
        else
        {
            $messages_array = array();
        }

        $messages = '';

        $marker = '';
        $separator = '';
        if(count($messages_array) > 1)
        {
            $marker = '* ';
            $separator = '<br />'."\n";
        }

        foreach($messages_array as $message)
        {

            $messages .= $marker;

            if($message['type'] == Enumerations\MessageType::ERROR)
            {
                $messages .= "<span class=\"error\">\n" . t('error:') . ' ';
            }

            $messages .= $message['text'] . $separator . "\n";

            if($message['type'] == Enumerations\MessageType::ERROR)
            {
                $messages .= '</span>'."\n";
            }
        }

        return $messages;
    }

    /**
     * Renders and prints a page as html
     * @param \Cms\Data\Page $page
     */
    function Render($page)
    {
        $theme = System::GetTheme();
        $theme_path = System::GetThemesPath();
        
        System::SetHTTPStatus($page->http_status_code);
        
        $title = $page->title;
        $content_title = $page->title;
        $content = self::ThemeContent($page);
        
        if($page->rendering_mode == Enumerations\PageRenderingMode::API)
        {
            print $content;
            return;
        }
        
        $base_url = System::GetBaseUrl();
        $language = System::GetDefaultLanguage();
        $meta = self::GetMetaTagsHTML($page);
        $styles = self::GetStylesHTML();
        $scripts = self::GetScriptsHTML();
        $messages = self::GetMessagesHTML();
        $tabs = self::GetTabsHTML();

        $html = '';

        ob_start();
            //This is a file that where user can create custom code for the template
            if(file_exists($theme_path . '/' . $theme . '/functions.php'))
            {
                include($theme_path . '/' . $theme . '/functions.php');
            }

            include(self::PageTemplate($page->uri));

            $html = ob_get_contents();
        ob_end_clean();

        print $html;
    }

    /**
     * Gets the best template match for a page.
     * @param string $uri
     * @return string Path of template file.
     */
    public static function PageTemplate($uri)
    {
        $theme = System::GetTheme();
        $theme_path = System::GetThemesPath();
        
        $uri = str_replace('/', '-', $uri);
        $segments = explode('-', $uri);

        $one_less_section = '';

        if(count($segments) > 1)
        {
            for($i=0; $i<(count($segments)-1); $i++)
            {
                $one_less_section .= $segments[$i] . '-';
            }
        }

        $globa_sections_page = $theme_path . '/' . $theme . '/page-' . $one_less_section . '.php';
        $current_page = $theme_path . '/' . $theme . '/page-' . $uri . '.php';
        $default_page = $theme_path . '/' . $theme . '/page.php';

        $template_path = '';

        if(file_exists($current_page))
        {
            $template_path = $current_page;
        }
        else if($one_less_section && file_exists($globa_sections_page))
        {
            $template_path = $globa_sections_page;
        }
        else
        {
            $template_path = $default_page;
        }

        return $template_path;
    }

    /**
     * Gets the best template match for a page content.
     * @param string $uri
     * @param string $type
     * @return string Path of template file.
     */
    public static function ContentTemplate($uri, $type)
    {
        $theme = System::GetTheme();
        $theme_path = System::GetThemesPath();
        
        $uri = str_replace('/', '-', $uri);

        $current_page = $theme_path . '/' . $theme . '/content-' . $uri . '.php';
        $content_type = $theme_path . '/' . $theme . '/content-' . $type . '.php';
        $default_page = $theme_path . '/' . $theme . '/content.php';

        $template_path = "";

        if(file_exists($current_page))
        {
            $template_path = $current_page;
        }
        elseif(file_exists($content_type))
        {
            $template_path = $content_type;
        }
        else
        {
            $template_path = $default_page;
        }

        return $template_path;
    }
}

?>
