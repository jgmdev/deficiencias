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
     * Handle to main site settings.
     * @var \Cms\Settings 
     */
    private static $settings;
    
    /**
     * Uri of the main homepage.
     * @var string
     */
    private static $home_page;
    
    /**
     * Flag to check if this class was correctly initialized before performing
     * any actions.
     * @var boolean 
     */
    private static $is_initialized;
    
    /**
     * Disable constructor
     */
    private function __construct(){}
    
    /**
     * Initializes the system settings handle.
     */
    public static function Init()
    {
        self::InitializeDataPath();
        
        self::$settings = new \Cms\Settings("main");
    }
    
    
    private static function InitializeDataPath()
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
            FileSystem::MakeDir(self::$data_path . 'settings', 0755, true);
            FileSystem::MakeDir(self::$data_path . 'sqlite', 0755, true);
        }
    }
    
    /**
     * Gets the path to directory that stores site pages, settings, etc...
     * @return type
     */
    public static function GetDataPath()
    {
        return self::$data_path;
    }
    
    public static function SetDataPath($path)
    {
        self::$data_path = rtrim($path, '/') . '/';
    }
    
    public static function GetDefaultLanguage()
    {
        self::InitializedCheck();
        
        $language = self::$settings->Get('language');
        
        return $language ? $language : "en";
    }
    
    /**
     * Set the language for translations.
     * @param string $language_code @see Localization\LanguageCode
     */
    public static function SetDefaultLanguage($language_code)
    {
        self::InitializedCheck();
        
        self::$settings->Add('language', $language_code);
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
    
    /**
     * Sets the path or uri of the home page
     * @param type $path
     */
    public static function SetHomePage($path)
    {
        self::$home_page = $path;
    }
    
    public static function GetBaseUrl()
    {
        static $base_url;
        
        if(strlen($base_url) > 0)
            return $base_url;
        
        $protocol = self::IsSSLConnection() ? "https" : "http";

        if(strstr($_SERVER["SCRIPT_NAME"], "index.php") !== false)
        {
            $paths = explode("/", $_SERVER["SCRIPT_NAME"]);
            unset($paths[count($paths) - 1]); //Remove index.php
            $path = implode("/", $paths);
        }
        else
        {
            //Correctly set base url path on hiphop
            $query = str_replace("p=", "", $_SERVER["QUERY_STRING"]);
            $query_elements = explode("&", $query);

            $path = rtrim(
                str_replace(
                    $query_elements[0],
                    "",
                    $_SERVER["SCRIPT_NAME"]
                ),
                "/"
            );
        }

        $base_url = $protocol . "://" . $_SERVER["HTTP_HOST"];
        $base_url .= $path;
        
        return $base_url;
    }
    
    /**
     * Sets the http status response code.
     * @param int $code @see \Cms\Enumerations\HTTPStatusCode
     */
    public static function SetHTTPStatus($code)
    {
        switch($code)
        {
            case 400:
                header("HTTP/1.1 400 Bad Request", true);
                break;
            case 401:
                 header("HTTP/1.1 401 Unauthorized", true);
                 break;
            case 403:
                header("HTTP/1.1 403 Forbidden", true);
                break;
            case 404:
                header("HTTP/1.1 404 Not Found", true);
                break;
            case 500:
                header("HTTP/1.1 500 Internal Server Error", true);
                break;

            case 200:
            default:
                header( "HTTP/1.1 200 OK", true);
        }
    }
    
    /**
     * Checks if the current connection is ssl.
     * @return boolean True on success false otherwise.
     */
    public static function IsSSLConnection()
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
        {
            return true;
        }

        return false;
    }
    
    /**
     * If System is not initialized throws exception.
     * @throws \Exception
     */
    private static function InitializedCheck()
    {
        if(!self::$is_initialized)
            throw new \Exception("You can not use this function without initializing the System class.");
    }
}

?>
