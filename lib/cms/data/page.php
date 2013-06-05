<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a page
 */
class Page
{
    public $title;
    
    public $content;
    
    public $type;
    
    public $author;
    
    public $created_date;
    
    public $last_edit_date;
    
    public $meta_title;
    
    public $description;
    
    public $keywords;
    
    public $http_status_code;
    
    /**
     * Groups that can view this page.
     * @var \Cms\Data\Group[]
     */
    public $groups;
    
    public function __construct()
    {
        $this->groups = array();
        
        $this->http_status_code = \Cms\Enumerations\HTTPStatusCode::OK;
    }
    
    /**
     * Adds a group that can view this page.
     * @param \Cms\Data\Group $group
     */
    public function AddGroup($group)
    {
        $this->groups[$group->machine_name] = $group;
    }
    
    /**
     * Removes a group
     * @param string $machine_name
     */
    public function RemoveGroup($machine_name)
    {
        unset($this->groups[$machine_name]);
    }
}

?>
