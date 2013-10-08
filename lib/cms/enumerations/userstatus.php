<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations;

/**
 * Basic mechanisms of ordering.
 */
class UserStatus
{
    const PENDING_APPROVAL = 0;
    const ACTIVE = 1;
    const BLOCKED = 2;
    
    public static function GetAll()
    {
        return array(
            self::PENDING_APPROVAL,
            self::ACTIVE,
            self::BLOCKED
        );
    }
    
    /**
     * Text representation of a status code. For example:
     * 0 == 'Pending Approval', 1 == 'Active', etc...
     * @param int $status_code
     * @return string
     */
    public static function GetLabel($status_code)
    {
        switch($status_code)
        {
            case 0:
                return t('Active');
            case 1:
                return t('Pending Approval');
            case 2:
                return t('Blocked');
        }
        
        return t('Unknown');
    }
}
?>
