<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

/**
 * A class to handle system wide settings.
 */
class System 
{
    /**
     * Directory where all site files reside.
     * @var string 
     */
    private static $data_path;
    
    /**
     * Uri of the main homepage.
     * @var string
     */
    private static $home_page;
    
    private function __construct();
    
    public static function GetDataPath()
    {
        if(!self::$data_path)
        {
            //For being able to run scripts from command line
            if(!isset($_SERVER["HTTP_HOST"]))
            {
                //Check if http host was passed on the command line
                if(isset($_REQUEST["HTTP_HOST"]))
                {
                    $_SERVER["HTTP_HOST"] = $_REQUEST["HTTP_HOST"];
                }

                //if not http_host passed then return default
                else
                {
                    self::$data_path = "data/default/";
                    return self::$data_path;
                }
            }

            $host = preg_replace("/^www\./", "", $_SERVER["HTTP_HOST"]);

            if(file_exists("data/" . $host))
            {
                self::$data_path = "data/" . $host . "/";
            }
            else
            {
                self::$data_path = "data/default/";
            }
        }
        
        if(!is_dir(self::$data_path))
        {
            FileSystem::MakeDir(self::$data_path, 0755, true);
            FileSystem::MakeDir(self::$data_path . 'users', 0755, true);
            FileSystem::MakeDir(self::$data_path . 'groups', 0755, true);
            FileSystem::MakeDir(self::$data_path . 'pages', 0755, true);
        }

        return self::$data_path;
    }
    
    public static function SetDataPath($path)
    {
        self::$data_path = rtrim($path, '/') . '/';
    }
    
    /**
     * Set the language for translations.
     * @param string $language_code @see Localization\LanguageCode
     */
    public static function SetLanguage($language_code)
    {
        
    }
    
    public static function GetLanguageCode()
    {
        
    }
    
    /**
     * Sets the path or uri of the home page
     * @param type $path
     */
    public static function SetHomePage($path)
    {
        self::$home_page = $path;
    }
    
    /**
     * Gets the current home page
     * @return string
     */
    public static function GetHomePage()
    {
        if(self::$home_page == "")
            return "";
        
        return self::$home_page;
    }
    
    public static function GetBaseUrl()
    {
        
    }
}

?>
