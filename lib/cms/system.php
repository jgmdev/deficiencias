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
    private static $data_path;
    
    private function __construct();
    
    public static function GetDataPath()
    {
        if(self::$data_path == "")
            throw new Exception("The path is not set. Please set it with: System::SetDataPath");
        
        return self::$data_path;
    }
    
    public static function SetDataPath($path)
    {
        self::$data_path = $path;
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
}

?>
