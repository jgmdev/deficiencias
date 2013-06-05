<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms
{
/**
 * A static class to handle system wide settings.
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
     * Flag to check if this class was correctly initialized before performing
     * any actions.
     * @var boolean 
     */
    private static $is_initialized;
    
    /**
     * Holds options used for coneccting to the relational database.
     * @var \Cms\DBAL\DataSource;
     */
    private static $datasource;
    
    /**
     * Reference to a database that can be used globally by the application.
     * @var \Cms\DBAL\DataBase;
     */
    private static $database;
    
    /**
     * Disable constructor
     */
    private function __construct(){}
    
    /**
     * Initializes the system settings handle.
     */
    public static function Init()
    {
        session_start();
        
        self::InitializeDataPath();
        
        self::$is_initialized = true;
        
        self::$settings = new \Cms\Settings("main");
    }
    
    /**
     * Creates all neccesary files and directory.
     * @return type
     */
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
            
            file_put_contents(
                self::$data_path . ".htaccess",
                "#disable download of any file\n".
                "<files *>\n".
                "   order allow,deny\n".
                "   deny from all\n".
                "</files>\n".
                "\n" .
                "# disable directory browsing\n" .
                "Options All -Indexes\n"
            );
            
            file_put_contents(
                self::$data_path . ".hiawatha",
                "AccessList = deny all\n"
            );
            
            file_put_contents(
                self::$data_path . "web.config",
                "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                "<configuration>\n".
                "    <system.webServer>\n".
                "        <rewrite>\n".
                "            <rules>\n".
                "                <rule name=\"Protect Directory By Crashing\" stopProcessing=\"true\">\n".
                "                    <match url=\"^(.*)$\" />\n".
                "                    <conditions>\n".
                "                         <add input=\"{REQUEST_FILENAME}\" matchType=\"IsFile\" />\n".
                "                         <add input=\"{REQUEST_FILENAME}\" matchType=\"IsDirectory\" />\n".
                "                    </conditions>\n".
                "                    <action type=\"Rewrite\" url=\"\" appendQueryString=\"false\" />\n".
                "                </rule>\n".
                "            </rules>\n".
                "        </rewrite>\n".
                "    </system.webServer>\n".
                "</configuration>\n"
            );
        }
    }
    
    /**
     * Gets the path to directory that stores site pages, settings, etc...
     * @return string
     */
    public static function GetDataPath()
    {
        self::InitializedCheck();
        
        return self::$data_path;
    }
    
    /**
     * Sets the directory where will reside all data files.
     * @param string $path
     */
    public static function SetDataPath($path)
    {
        self::$data_path = rtrim($path, '/') . '/';
    }
    
    /**
     * Get the default language as to display the site.
     * @return string @see \Cms\Enumerations\LanguageCode
     */
    public static function GetDefaultLanguage()
    {
        self::InitializedCheck();
        
        $language = self::$settings->Get('language');
        
        return $language ? $language : "en";
    }
    
    /**
     * Set the language the application is going to be displayed as.
     * @param string $language_code @see \Cms\Enumerations\LanguageCode
     */
    public static function SetDefaultLanguage($language_code)
    {
        self::InitializedCheck();
        
        self::$settings->Add('language', $language_code);
    }
    
    /**
     * Gets the uri of home page
     * @return string
     */
    public static function GetHomePage()
    {
        self::InitializedCheck();
        
        $home_page = self::$settings->Get("home_uri");
        
        if($home_page == '')
            return 'home';
        
        return $home_page;
    }
    
    /**
     * Sets the path or uri of the home page
     * @param string $path
     */
    public static function SetHomePage($uri)
    {
        self::InitializedCheck();
        
        self::$settings->Add('home_uri', $uri);
    }
    
    /**
     * Get the theme that is going to be used to render pages.
     * @return string
     */
    public static function GetTheme()
    {
        $theme = self::$settings->Get('theme');
        
        if($theme)
            return $theme;
        
        return 'default';
    }
    
    /**
     * Set the theme that is going to be used to render pages.
     * @param string $name
     */
    public static function SetTheme($name)
    {
        self::$settings->Add('theme', $name);
    }
    
    /**
     * Get the path where themes reside.
     * @return string
     */
    public static function GetThemesPath()
    {
        $themes_path = self::$settings->Get('themes_path');
        
        if($themes_path)
            return $themes_path;
        
        return 'themes';
    }
    
    /**
     * Set the path where themes reside.
     * @param string $path
     */
    public static function SetThemesPath($path)
    {
        self::$settings->Add('themes_path', $path);
    }
    
    /**
     * Get the path where translation files reside.
     * @return string
     */
    public static function GetTranslationsPath()
    {
        $translations_path = self::$settings->Get('translations_path');
        
        if($translations_path)
            return $translations_path;
        
        return 'locale';
    }
    
    /**
     * Set the path where translation files reside.
     * @param string $path
     */
    public static function SetTranslationsPath($path)
    {
        self::$settings->Add('translations_path', $path);
    }
    
    /**
     * Set the relational database data source.
     * @param \Cms\DBAL\DataSource $datasource
     */
    public static function SetDataSource(\Cms\DBAL\DataSource $datasource)
    {
        if(self::$database)
        {
            self::$database->Disconnect();
        }
        
        self::$database = new DBAL\DataBase($datasource);
    }
    
    /**
     * Get a database that can be shared globally by the application.
     * @return \Cms\DBAL\DataBase
     * @throws Exception
     */
    public function GetRelationalDatabase()
    {
        if(!self::$database)
        {
            throw new Exception(t("You have to set the data source before getting the database."));
        }
        
        return self::$database;
    }
    
    /**
     * Gets the base url where the application is running.
     * @staticvar string $base_url To improve performance on successive calls.
     * @return string
     */
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
     * Checks if the current transfer type is ssl.
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
}

namespace
{
    /**
     * Register global function for translating and to facilitate automatic
     * generation of po files by tools like poedit.
     * @param string text
     * @return string Translation if found.
     */
    function t($text)
    {
        static $language_object;

        if(!$language_object)
        {
            $language_object = new Cms\Language(Cms\System::GetTranslationsPath());
        }

        return $language_object->Translate($text);
    }
}
?>
