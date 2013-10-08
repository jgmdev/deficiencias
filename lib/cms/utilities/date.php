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

    public static function GetDays()
    {
        $days = array();

        for($i = 1; $i <= 31; $i++)
        {
            $days[$i] = $i;
        }

        return $days;
    }

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

}

?>
