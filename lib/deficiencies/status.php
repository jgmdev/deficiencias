<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

/**
 * List of deficiency status.
 */
class Status
{

    const ISNEW = 0;
    const ASSIGNED = 1;
    const PENDING = 2;
    const CLOSED = 3;
    const REJECTED = 4;
    const REOPEN = 5;

    public static function getAll()
    {
        $def_status = array(
            self::ISNEW => t('New'),
            self::ASSIGNED => t('Assigned'),
            self::PENDING => t('Pending'),
            self::CLOSED => t('Closed'),
            self::REJECTED => t('Rejected'),
            self::REOPEN => t('Re-opened')
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
