<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

use Cms;

/**
 * Facilitate the management of attendants which are groups of people 
 * responsible to follow up the reported deficiencies by city.
 */
class Attendants
{
    /**
     * Disable constructor
     */
    private function __construct(){}
    
    /**
     * Check if a given group is an attendant.
     * @param string $group
     * @return boolean
     */
    public static function GroupIsAttendant($group)
    {
        $attendants = self::Get();
        
        if(is_array($attendants))
        {
            if(in_array($group, $attendants))
                return true;
        }
        
        return false;
    }
    
    /**
     * Check if a given user is an attendant.
     * @param string $username
     * @return boolean
     */
    public static function UserIsAttendant($username)
    {
        $user_data = Cms\Users::GetData($username);
        
        return self::Get($user_data->group);
    }
    
    /**
     * Gets an array of groups which are all attendants.
     * @return array
     */
    public static function Get()
    {   
        $groups = Cms\Groups::GetList();
        
        $attendants = array();
        
        /* @var $group \Cms\Data\Group */
        foreach($groups as $group)
        {
            if($group->HasPermission(Permissions::ATTENDANT))
            {
                $attendants[] = $group->machine_name;
            }
        }
        
        return $attendants;
    }
    
    /**
     * Assign cities to a group of attendants.
     * @param string $group
     * @param array $cities
     */
    public static function SetCities($group, array $cities)
    {
        $settings = new Cms\Settings('deficiencies');
        $settings->Add($group.'_cities', $cities);
    }
    
    /**
     * Get the cities assigned to a group of attendants.
     * @param string $group
     */
    public static function GetCities($group)
    {
        $settings = new Cms\Settings('deficiencies');
        $cities = unserialize($settings->Get($group.'_cities'));
        
        if(!is_array($cities))
            return array();
        
        return $cities;
    }
    
    /**
     * Get a list of city attendants that can be assigned to follow up
     * a report deficiency.
     * @param string $city
     * @return array Format of array is array('Fullname'=>'username', ...)
     */
    public static function GetCityAttendants($city)
    {
        $attendants = array();
        
        $groups = self::Get();
        
        foreach($groups as $machine_name)
        {
            $cities = self::GetCities($machine_name);
            
            if(in_array($city, $cities))
            {
                $db = Cms\System::GetRelationalDatabase();
                
                $select = new \Cms\DBAL\Query\Select('users');
                $select->SelectAll()
                    ->WhereEqual('user_group', $machine_name, Cms\Enumerations\FieldType::TEXT)
                ;
                
                $db->Select($select);
                
                while($data = $db->FetchArray())
                {
                    $attendants[$data['fullname']] = $data['username'];
                }
            }
        }
        
        return $attendants;
    }
}


?>