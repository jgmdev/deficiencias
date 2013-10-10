<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Data;

/**
 * Represents a list of pages. This class was especially crafted to generate
 * the control center page.
 */
class GenericList
{   
    public $groups;
    
    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->groups = array();
    }
    
    /**
     * Add a new group of pages.
     * @param string $name
     * @param \Cms\Data\Page[] $pages
     */
    public function AddGroup($name, $pages)
    {
        $this->groups[$name] = array();
        
        foreach($pages as $page)
        {
            $this->groups[$name][$page->uri] = $page;
        }
    }
    
    /**
     * Add a page to an existing group of pages. Creates group 
     * if doesn't exists.
     * @param string $group_name
     * @param \Cms\Data\Page $page
     */
    public function AddPage($group_name, $page)
    {
        if(!isset($this->groups[$group_name]))
        {
            $this->groups[$group_name] = array();
        }
        
        $this->groups[$group_name][$page->uri] = $page;
    }
    
    /**
     * Add a page after another page with the matching given uri.
     * @param \Cms\Data\Page $page
     * @param string $other_page_uri
     * @return boolean False if no group contained the other page uri.
     */
    public function AddPageAfter($page, $other_page_uri)
    {
        return $this->AppendPage($page, $other_page_uri);
    }
    
    /**
     * Add a page before another page with the matching given uri.
     * @param \Cms\Data\Page $page
     * @param string $other_page_uri
     * @return boolean False if no group contained the other page uri.
     */
    public function AddPageBefore($page, $other_page_uri)
    {
        return $this->AppendPage($page, $other_page_uri, false);
    }
    
    /**
     * Add a group after another group with the matching given name.
     * @param string $group_name
     * @param \Cms\Data\Page[] $pages
     * @param string $other_group_name
     * @return boolean False if other group name doesn't exists.
     */
    public function AddGroupAfter($group_name, $pages, $other_group_name)
    {
        $this->AppendGroup($group_name, $pages, $other_group_name);
    }
    
    /**
     * Add a group before another group with the matching given name.
     * @param string $group_name
     * @param \Cms\Data\Page[] $pages
     * @param string $other_group_name
     * @return boolean False if other group name doesn't exists.
     */
    public function AddGroupBefore($group_name, $pages, $other_group_name)
    {
        $this->AppendGroup($group_name, $pages, $other_group_name, false);
    }
    
    /**
     * Add a page after another page with the matching given uri.
     * @param \Cms\Data\Page $page
     * @param string $other_page_uri
     * @param boolean $after If false it adds the page before the given
     * page uri.
     * @return boolean False if other page uri doesn't exists.
     */
    protected function AppendPage($page, $other_page_uri, $after=true)
    {
        $page_added = false;
        
        foreach($this->groups as $group_name=>&$pages)
        {   
            if(isset($pages[$other_page_uri]))
            {
                $pages_copy = array();
                
                foreach($pages as $page_uri=>$current_page)
                {
                    if($page_uri == $other_page_uri && !$after)
                    {
                        $pages_copy[$page->uri] = $page;
                        $page_added = true;
                    }
                    
                    $pages_copy[$page_uri] = $current_page;
                    
                    if($page_uri == $other_page_uri && $after)
                    {
                        $pages_copy[$page->uri] = $page;
                        $page_added = true;
                    }
                }
                
                $pages = $pages_copy;
                break;
            }
        }
        
        return $page_added;
    }
    
    /**
     * Add a group after another group with the matching given name.
     * @param string $group_name
     * @param \Cms\Data\Page[] $pages
     * @param string $other_group_name
     * @param boolean $after If false it adds the page before the given
     * page uri.
     * @return boolean False if other group name doesn't exists.
     */
    protected function AppendGroup($group_name, $pages, $other_group_name, $after=true)
    {
        $group_added = false;
        
        if(isset($this->groups[$other_group_name]))
        {
            $groups_copy = array();
            
            foreach($this->groups as $current_group_name=>$current_group_pages)
            {
                if($current_group_name == $other_group_name && !$after)
                {
                    $groups_copy[$group_name] = $pages;
                }
                    
                $groups_copy[$current_group_name] = $current_group_pages;
                
                if($current_group_name == $other_group_name && $after)
                {
                    $groups_copy[$group_name] = $pages;
                }
            }
            
            $this->groups = $groups_copy;
            $group_added = true;
        }
        
        return $group_added;
    }
}

?>
