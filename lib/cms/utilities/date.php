<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Utilities;

/**
 * Helper date functions.
 */
class Date
{

    /**
     * Gets an array of numbers from 1 to 31 ready to use on a 
     * select form field @see \Cms\Form\Field\Select
     * @return int[]
     */
    public static function GetDays()
    {
        $days = array();

        for($i = 1; $i <= 31; $i++)
        {
            $days[$i] = $i;
        }

        return $days;
    }

    /**
     * Gets an array of months from january to december ready to use on a 
     * select form field @see \Cms\Form\Field\Select
     * @return int[] Format of array is array('Month Name'=>month_number)
     */
    public static function GetMonths()
    {
        $months = array();

        $months[t('January')] = 1;
        $months[t('February')] = 2;
        $months[t('March')] = 3;
        $months[t('April')] = 4;
        $months[t('May')] = 5;
        $months[t('June')] = 6;
        $months[t('July')] = 7;
        $months[t('August')] = 8;
        $months[t('September')] = 9;
        $months[t('October')] = 10;
        $months[t('November')] = 11;
        $months[t('December')] = 12;

        return $months;
    }

    /**
     * Gets an array of years from 1900 to current year ready to use on a 
     * select form field @see \Cms\Form\Field\Select
     * @return int[]
     */
    public static function GetYears()
    {
        $current_year = date("Y", time());
        $years = array();

        for($i = 1900; $i <= $current_year; $i++)
        {
            $years[$i] = $i;
        }

        arsort($years);

        return $years;
    }
    
    /**
     * Get the amount of time in a easy to read human format.
     * @param int $timestamp
     * @return string Example: '10 days ago'
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

                return str_replace(
                    array('{time}', '{period}'), 
                    array($time, $period), 
                    t('{time} {period} ago')
                );
            }
        }
    }

}

?>
