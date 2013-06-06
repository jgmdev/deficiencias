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
    
    /**
     * Get the amount of time in a easy to read human format.
     * @param timestamp $timestamp
     * @return string
     */
    public static function GetTimeElapsed($timestamp)
    {
        $etime = time() - $timestamp;

        if ($etime < 1)
        {
            return t('0 seconds');
        }

        $a = array(
            12 * 30 * 24 * 60 * 60 => array(t('year'), t('years')),
            30 * 24 * 60 * 60 => array(t('month'), t('months')),
            24 * 60 * 60 => array(t('day'), t('days')),
            60 * 60 => array(t('hour'), t('hours')),
            60 => array(t('minute'), t('minutes')),
            1 => array(t('second'), t('seconds'))
        );

        foreach ($a as $secs => $labels)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $time = round($d);

                if($time > 1)
                    $period = $labels[1];
                else
                    $period = $labels[0];

                return str_replace(array("{time}", "{period}"), array($time, $period), t('{time} {period} ago'));
            }
        }
    }
}

?>
