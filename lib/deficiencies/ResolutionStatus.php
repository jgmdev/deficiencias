<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

/**
 * List of deficiency status.
 */
class ResolutionStatus
{

    const UNFIXED = 0;
    const FIXED = 1;
    const IN_PROCESS = 2;

    public static function getAll()
    {
        $def_status = array(
            self::UNFIXED => t('Unfixed'),
            self::FIXED => t('Fixed'),
            self::IN_PROCESS => t('In process'),
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
