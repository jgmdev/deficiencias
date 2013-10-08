<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

/**
 * Functions to handle paths.
 */
class Uri
{
    /**
     * Disable constructor
     */
    private function __construct() {}
    
    /**
     * Gets the current uri.
     * 
     * @return string home page if $_REQUEST['p'] is null or the $_REQUEST['p'] value.
     */
    public static function GetCurrent()
    {
        static $page;

        if($page == '')
        {
            //Default home page.
            $page = 'home';

            //Try to get home page set on site settings
            if($home_page = System::GetHomePage())
            {
                $page = $home_page;
            }

            if(isset($_REQUEST['p']))
            {
                $_REQUEST['p'] = rtrim($_REQUEST['p'], '/');
                
                if($_REQUEST['p'] != '')
                {
                    $page = $_REQUEST['p'];
                }
            }
        }

        return $page;
    }

    /**
     * This functions generates an url based on the current base url, as check
     * if the uri paramenter is a full address like http://somedomain.com and just return it.
     * @param string $uri The page address that we want to print of full http address.
     * @param array $arguments The variables that we are going to pass to the page in
     * the format variables["name"] = "value"
     * @return string A formatted url.
     * Example of clean url: mydomain.com/page?argument=value.
     * Without clean url mydomain.com/?p=page&argument=value
     */
    public static function GetUrl($uri, $arguments = array())
    {
        $url = "";

        if("" . strpos($uri, "http://") . "" != "" || "" . strpos($uri, "https://") . "" != "")
        {
            $url = $uri;
        }
        else
        {
            $url = System::GetBaseUrl() . "/" . $uri;

            if(count($arguments) > 0)
            {
                $formated_arguments = "?";

                foreach($arguments as $argument=>$value)
                {
                    if("" . $value . "" != "")
                    {
                        $formated_arguments .= $argument . "=" . rawurlencode($value) . "&";
                    }
                }

                $formated_arguments = rtrim($formated_arguments, "&");

                $url .= $formated_arguments;
            }
        }

        return $url;
    }
    
    /**
     * Stops php script execution and redirects to a new page.
     * @param string $uri The page we are going to redirect.
     * @param array $arguments Arguments to pass to the url in te format $arguments["name"] = "value"
     * @param $ssl Use ssl protocol when going to the page.
     */
    public static function Go($uri, $arguments = null, $ssl=false)
    {
        ob_clean();
        
        if(!$ssl)
            header("Location: " . self::GetUrl($uri, $arguments));
        else
            header("Location: " . str_replace("http://", "https://", self::GetUrl($uri, $arguments)));

        exit;
    }

    /**
     * Convertes any given string into a conventional path.
     * @param string $string The string to convert.
     * @param bool $allow_slashes If true, does not strip outs slashes (/).
     * @return string uri ready to use
     */
    public static function TextToUri($string, $allow_slashes=false)
    {   
        $uri = $string;

        $uri = str_ireplace(
            array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ",
            "Á", "É", "Í", "Ó", "Ú", "Ä", "Ë", "Ï", "Ö", "Ü", "Ñ"), 
            array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n",
            "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
            $uri
        );

        $uri = trim($uri);

        $uri= strtolower($uri);

        // only take alphanumerical characters, but keep the spaces, dots and dashes
        if(!$allow_slashes)
            $uri= preg_replace('/[^a-zA-Z0-9\. -]/', '', $uri );

        // only take alphanumerical characters, but keep the spaces, dots, dashes and slashes
        else
            $uri= preg_replace('/[^a-zA-Z0-9\. -\/]/', '', $uri );

        $uri= str_replace(' ', '-', $uri);

        //Replace consecutive dashes by a single one
        $uri = preg_replace('/([-]+)/', '-', $uri);
        
        //Replace consecutive dots by a single one
        $uri = preg_replace('/([\.]+)/', '.', $uri);

        return $uri;
    }
}

?>
