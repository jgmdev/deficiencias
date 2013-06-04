<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a user
 */
class User
{
    /**
     * @var string
     */
    public $username;
    
    /**
     * @var string
     */
    public $email;
    
    /**
     * @var string
     */
    public $fullname;
    
    /**
     * @var string
     */
    public $group;
    
    /**
     * @var string
     */
    public $status;
    
    /**
     * @var int
     */
    public $registration_date;
    
    /**
     * @var string
     */
    public $gender;
    
    /**
     * @var int
     */
    public $birth_date;
    
    /**
     * @var string
     */
    public $picture;
    
    function GetPicturePath()
    {
        
    }

    function GetPictureUrl()
    {
        
    }
    
    function GetStatus()
    {
        $status = array();

        $status[t("Active")] = "1";
        $status[t("Pending Approval")] = "0";
        $status[t("Blocked")] = "2";

        return $status;
    }
}

?>
