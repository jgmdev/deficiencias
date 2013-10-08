<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Form\Field;
use Cms\Enumerations\FormFieldType;

class Radio extends Field
{
    /**
     * List of radio options.
     * @var array
     */
    public $options;
    
    /**
     * Default constructor.
     * @param string $main_label
     * @param string $name
     * @param array $options
     * @param string $selected
     * @param string $description
     * @param boolean $required
     * @param boolean $readonly
     * @param int $size
     */
    public function __construct($main_label, $name, array $options=array(), $selected='', $description='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($main_label, $name, $selected, $description, null, FormFieldType::RADIO, $required, $readonly, $size);
        
        $this->options = $options;
    }
    
    /**
     * Add radio option.
     * @param string $label
     * @param string $option
     */
    public function AddOption($label, $value)
    {
        $this->options[$label] = $value;
    }
    
    public function GetHtml()
    {
        $request_value = $this->GetRequestValue();
        
        $html = '';
        
        $id = 0;
        foreach($this->options as $label=>$value)
        {
            $checked = false;
            
            if($request_value == $value)
                $checked = true;
            elseif($request_value == null)
            {
                if($this->value == $value)
                    $checked = true;
            }
            
            $html .= '<input type="'.$this->type.'" ';
            $html .= 'id="'.$this->id.'-'.$id.'" ';
            $html .= 'name="'.$this->name.'" ';

            if($checked)
                $html .= 'checked ';

            if($value)
                $html .= 'value="'.$value.'" ';

            if($this->readonly > 0)
                $html .= 'readonly ';

            if($this->size > 0)
                $html .= 'size="'.$this->size.'" ';

            if(count($this->attributes) > 0)
            {
                foreach($this->attributes as $name=>$value)
                {
                    $html .= $name.'="'.$value.'" ';
                }
            }

            $html .= '/>' . "\n";
            
            $html .= '<label class="'.$this->type.'" for="'.$this->id.'-'.$id.'">';
            $html .= $label;
            $html .= '</label>' . "\n";
            
            $id++;
        }
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
    
    public function GetLabelHtml()
    {   
        $html = '';
        
        if($this->label)
        {
            $html .= '<label>';
            $html .= $this->label;

            if($this->required)
                $html .= ' <span class="required">*</span>';

            $html .= '</label>' . "\n";
        }
        
        return $html;
    }
}
?>
