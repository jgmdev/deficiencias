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
    public $password;
    
    /**
     * @var string
     */
    public $ip;
    
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
    
    
    public function GetPicturePath()
    {
        
    }

    public function GetPictureUrl()
    {
        
    }
}

?>
