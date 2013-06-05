<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

/**
 * Misc functions should go here.
 */
class Utilities
{
    /**
     * Disable constructor
     */
    private function __construct() {}
    
    /**
     * Parses a string as actual php code using the eval function
     * @param string $text The string to be parsed.
     * @return string The evaluated output captured by ob_get_contents function.
     */
    public static function PHPEval($text)
    {
        //Prepares the text to be evaluated
        $text = trim($text, "\n\r\t\0\x0B ");

        ob_start();
            eval('?>' . $text);
            $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

?>
