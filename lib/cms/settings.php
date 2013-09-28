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
     * Directory where setting tables are stored
     * @var string
     */
    private $table_path;
    
    /**
     * Initializes the settings object.
     * @param string $table The name of the settings table, for example: main
     * @param string $path Path where table resides. Default: data/settings
     */
    public function __construct($table, $path = '')
    {
        $this->tables_array = array();
        
        if($path)
            $this->table_path = rtrim($path, "\\/") . "/";
        else
            $this->table_path = System::GetDataPath() . "settings/";
        
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
        
        $this->data = new Data($this->table_path . "$table.php");
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
}

?>
