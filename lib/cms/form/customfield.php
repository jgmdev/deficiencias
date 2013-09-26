<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class CustomField extends Field
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
