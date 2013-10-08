<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

use Cms\Enumerations\HTTPStatusCode;
use Cms\Enumerations\PageRenderingMode;

/**
 * Represents a page
 */
class Page
{
    /**
     * Public path to access the page.
     * Example: my-section/my-page
     * @var string
     */
    public $uri;
    
    /**
     * Main title of the page.
     * @var string
     */
    public $title;
    
    /**
     * Main content of the page.
     * @var string
     */
    public $content;
    
    /**
     * Page of time @todo Need to implement content types.
     * @var string
     */
    public $type;
    
    /**
     * Original creator/author of page.
     * @var string
     */
    public $author;
    
    /**
     * Time when page was created
     * @var int
     */
    public $created_date;
    
    /**
     * Last time the page was modified
     * @var int
     */
    public $last_edit_date;
    
    /**
     * Title used to generate the html meta data.
     * @var string
     */
    public $meta_title;
    
    /**
     * Description used to generate the html meta data.
     * @var string
     */
    public $description;
    
    /**
     * Comma seperated words used to generate the html meta data.
     * @var string
     */
    public $keywords;
    
    /**
     * Holds one of the values from \Cms\Enumerations\HTTPStatusCode
     * @var string
     */
    public $http_status_code;
    
    /**
     * Holds one of the values from \Cms\Enumerations\PageRenderingMode
     * @var string
     */
    public $rendering_mode;
    
    /**
     * Groups that can view this page.
     * @var array
     */
    public $groups;
    
    /**
     * A list of permissions needed to access the page.
     * @see \Cms\Enumerations\Permissions
     * @var array 
     */
    public $permissions;
    
    /**
     * Default constructor.
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        
        $this->groups = array();
        
        $this->permissions = array();
        
        $this->rendering_mode = PageRenderingMode::NORMAL;
        
        $this->http_status_code = HTTPStatusCode::OK;
    }
    
    /**
     * Adds a group that can view this page.
     * @param string $machine_name
     */
    public function AddGroup($machine_name)
    {
        $this->groups[$machine_name] = true;
    }
    
    /**
     * Removes a group access to this page.
     * @param string $machine_name
     */
    public function RemoveGroup($machine_name)
    {
        unset($this->groups[$machine_name]);
    }
    
    /**
     * Checks if a given group can access this page.
     * If page is not assigned to any group this function
     * will always return true.
     * @param string $machine_name
     * @return boolean
     */
    public function GroupHasAccess($machine_name)
    {
        if(count($this->groups) <= 0)
            return true;
        
        if(isset($this->groups[$machine_name]))
            return true;
        
        return false;
    }
    
    /**
     * Adds a permission that is required to view this page.
     * @param string $identifier @see \Cms\Enumerations\Permissions
     */
    public function AddPermission($identifier)
    {
        $this->permissions[$identifier] = $identifier;
    }
    
    /**
     * Removes a permission that is required to view this page.
     * @param string $identifier @see \Cms\Enumerations\Permissions
     */
    public function RemovePermission($identifier)
    {
        unset($this->permissions[$identifier]);
    }
}

?>
