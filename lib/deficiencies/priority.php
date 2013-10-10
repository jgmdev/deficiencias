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
    
    public static function GetLabels()
    {
        $priorities = array(
            t('Low')=>1,
            t('Medium')=>2,
            t('High')=>3,
            t('Urgent')=>4
        );
        
        return $priorities;
    }
}
?>
