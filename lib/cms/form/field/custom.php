<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Form\Field;

class Custom extends Field
{   
    /**
     * Custom html code.
     * @var string
     */
    public $html;
    
    /**
     * Default constructor.
     * @param string $html
     */
    public function __construct($html)
    {
        parent::__construct('', '');
        
        $this->html = $html;
    }
    
    public function GetHtml()
    {
        return $this->html;
    }
    
    public function GetLabelHtml()
    {
        return '';
    }
    
    public function GetSingleHtml($value = '')
    {
        return '';
    }
}
?>
