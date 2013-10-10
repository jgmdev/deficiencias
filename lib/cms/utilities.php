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
     * @param int $timestamp
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

                return str_replace(array('{time}', '{period}'), array($time, $period), t('{time} {period} ago'));
            }
        }
    }
    
    /**
     * Generates a generic navigation bar for any kind of results
     * 
     * @param integer $total_count The total amount of results.
     * @param integer $page The actual page number displaying results.
     * @param string $uri The uri used on navigation bar links.
     * @param string $module Optional module name to generate uri.
     * @param integer $amount Optional amount of results to display per page, Default: 30
     * @param array $arguments Optional arguments to pass to the navigation links.
     */
    function GenerateNavigation($total_count, $page, $uri, $module = "", $amount = 30, $arguments = array())
    {
        $html = '';
        
        $page_count = 0;
        $remainder_pages = 0;
        $arguments = array();

        if($total_count <= $amount)
        {
            $page_count = 1;
        }
        else
        {
            $page_count = floor($total_count / $amount);
            $remainder_pages = $total_count % $amount;

            if($remainder_pages > 0)
            {
                $page_count++;
            }
        }

        //In case someone is trying a page out of range or not print if only one page
        if($page > $page_count || $page < 0 || $page_count == 1)
        {
            return '';
        }

        $html .= '<div class="pages-navigation">' . "\n";
        $html .= "\t" . '<div class="pages">' . "\n";
        
        if($page != 1)
        {
            $arguments['page'] = $page - 1;
            $previous_page = Uri::GetUrl(Module::GetUri($uri, $module), $arguments);
            $previous_text = t('Previous');
            $html .= "\t\t<a class=\"previous\" href=\"$previous_page\">$previous_text</a>\n";
        }

        $start_page = $page;
        $end_page = $page + 10;

        for($start_page; $start_page < $end_page && $start_page <= $page_count; $start_page++)
        {
            $text = t($start_page);

            if($start_page > $page || $start_page < $page)
            {
                $arguments['page'] = $start_page;
                $url = Uri::GetUrl(Module::GetUri($uri, $module), $arguments);
                $html .= "\t\t<a class=\"page\" href=\"$url\">$text</a>\n";
            }
            else
            {
                $html .= "\t\t<a class=\"current-page page\">$text</a>\n";
            }
        }

        if($page < $page_count)
        {
            $arguments['page'] = $page + 1;
            $next_page = Uri::GetUrl(Module::GetUri($uri, $module), $arguments);
            $next_text = t('Next');
            $html .= "\t\t<a class=\"next\" href=\"$next_page\">$next_text</a>\n";
        }
        
        $html .= "\t" . '</div>' . "\n";
        $html .= '</div>' . "\n";
        
        return $html;
    }
}

?>
