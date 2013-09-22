<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a module information
 */
class ModuleInfo
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var string
     */
    public $version;
    
    /**
     * @var string
     */
    public $author;
    
    /**
     * @var string
     */
    public $email;
    
    /**
     * @var string
     */
    public $website;
    
    /**
     * @var array
     */
    public $contributors;
    
    /**
     * @var array
     */
    public $dependencies;
    
    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->contributors = array();
        $this->dependencies = array();
    }
    
    /**
     * Check if the module requires other modules to be installed.
     * @return boolean
     */
    public function HasDependency()
    {
        if(count($this->dependencies) > 0)
            return true;
        
        return false;
    }
}

?>
