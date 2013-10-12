<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;


/**
 * Levels of urgency
 */
class Priority
{
    const LOW = 1;
    const MEDIUM = 2;
    const HIGH = 3;
    const URGENT = 4;
    
    public static function getAll()
    {
        $def_status = array(
            self::LOW => t('Low'),
            self::MEDIUM => t('Medium'),
            self::HIGH => t('High'),
            self::URGENT => t('Urgent')
        );

        return $def_status;
    }

    public static function getStatus($id)
    {
        $def_status = self::getAll();
        return (isset($def_status[$id])) ? $def_status[$id] : null;
    }
}
?>
