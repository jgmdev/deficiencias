<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

/**
 * Facilitate management of configurations or settings.
 */
class Settings
{
    /**
     * Name of the settings table
     * @var string
     */
    private $table;
    
    /**
     * Stores the settings data object
     * @var \Cms\Data
     */
    private $data;
    
    /**
     * Var that stores previously parsed tables for better performance.
     * @var array
     */
    private $tables_array;
    
    /**
     * Initializes the settings object.
     * @param string $table The name of the settings table, for example: main
     */
    public function __construct($table)
    {
        $this->tables_array = array();
        
        $this->Load($table);
    }
    
    /**
     * Loads a new table into the settings object.
     * @param string $table
     */
    public function Load($table)
    {
        $table = trim($table);
        $this->table = $table;
        
        if(is_string($table))
            $this->table = $table;
        
        $this->data = new Data(System::GetDataPath() . "settings/$table.php");
    }
    
    /**
     * Stores a configuration option on a database file and creates it if doesnt exist.
     * @param string $name Configuration name.
     * @param string $value Configuration value.
     * @param string $table Name of database configuration file stored on data/settings.
     */
    function Add($name, $value)
    {
        $fields = array();
        
        $fields['name'] = $name;
        
        if(is_string($value))
            $fields['value'] = $value;
        else
            $fields['value'] = serialize($value);
        
        $current_settings = $this->data->GetAllRows();

        $setting_exists = false;
        $setting_id = 0;

        if(count($current_settings) > 0)
        {
            foreach($current_settings as $id=>$setting)
            {
                if(trim($setting['name']) == $name)
                {
                    $setting_exists = true;
                    $setting_id = $id;
                    break;
                }
            }
        }

        if($setting_exists)
        {
            $this->data->EditRow($setting_id, $fields);
        }
        else
        {
            $this->data->AddRow($fields);
        }
    }

    /**
     * Gets a configuration value from a database file.
     * @param string $name Configuration to retrieve.
     * @return string Configuration value or empty string.
     */
    function Get($name)
    {
        $settings = $this->data->GetAllRows();

        $value = '';

        foreach($settings as $setting)
        {
            if($setting['name'] == $name)
            {
                $value = $setting['value'];
                break;
            }
        }

        return $value;
    }

    /**
     * Gets all the configurations values from a data file.
     * @return array All configurations in the format:
     * configurations[name] = value.
     */
    function GetAll()
    {
        $settings_data = $this->data->GetAllRows();

        $settings = array();

        foreach($settings_data as $setting)
        {
            $settings[$setting['name']] = $setting['value'];
        }

        return $settings;
    }

    /**
     * Checks if settings.php values should be override by data base settings file
     * main stored on data/settings/main.php.
     */
    function settings_override()
    {
        global $title, $base_url, $slogan, $footer_message, $theme, $theme_path, $language, $clean_urls, $user_profiles;

        if($settings = get_settings("main"))
        {
            if($settings["override"])
            {
                $title = $settings["title"]?$settings["title"]:$title;

                if($settings["timezone"])
                {
                    date_default_timezone_set($settings["timezone"]);
                } 

                $protocol = is_ssl_connection() ? "https" : "http";

                if($settings["auto_detect_base_url"] || trim($settings["base_url"]) == "")
                {
                    if(
                        strstr($_SERVER["SCRIPT_NAME"], "index.php") !== false ||
                        strstr($_SERVER["SCRIPT_NAME"], "cron.php") !== false ||
                        strstr($_SERVER["SCRIPT_NAME"], "uris.php") !== false ||
                        strstr($_SERVER["SCRIPT_NAME"], "upload.php") !== false
                    )
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
                }
                else
                {
                    $base_url = str_replace("http://", "$protocol://", $settings["base_url"]?$settings["base_url"]:$base_url);
                }

                $user_profiles = $settings["user_profiles"]?$settings["user_profiles"]:$user_profiles;
                $slogan = $settings["slogan"]?$settings["slogan"]:$slogan;
                $footer_message = $settings["footer_message"]?$settings["footer_message"]:$footer_message;
                $theme = $settings["theme"]?$settings["theme"]:$theme;
                $language = $settings["language"]?$settings["language"]:$language;
                $clean_urls = $settings["clean_urls"];

                $theme_path = $base_url . "/themes/" . $theme;
            }
        }
    }
}

?>
