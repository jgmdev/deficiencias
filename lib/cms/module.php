<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

class Module
{
    public static function Install($name)
    {
        
    }
    
    public static function Uninstall($name)
    {
        
    }
    
    public static function CheckDependencies()
    {
        
    }
    
    public static function IsDependency($name)
    {
        
    }
    
    /**
     * Get information of a module.
     * @param string $name
     * @return \Cms\Data\ModuleInfo
     */
    public static function GetInfo($name)
    {
        $module_dir = self::GetPath($name);

        $info_file = $module_dir . "info.php";

        $module_info = new Data\ModuleInfo;
        
        if(file_exists($info_file))
        {
            include($info_file);
            
            foreach($module as $name=>$value)
            {
                $module_info->$name = $value;
            }
        }

        return $module_info;
    }
    
    /**
     * Get a list of available modules for installation.
     * @return array
     */
    public static function GetAvailable()
    {
        
    }
    
    /**
     * Get a list of installed modules.
     * @return array
     */
    public static function GetInstalled()
    {
        
    }
    
    /**
     * Get the path of a module.
     * @param string $name
     */
    public static function GetPath($name)
    {
        return "modules/$name/";
    }
}

?>
