<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Field;

use Cms\Form\Field;
use Cms\Enumerations\FormFieldType;

/**
 * A form select element.
 */
class Select extends Field
{
    /**
     * List of options for the select box.
     * @var array
     */
    public $options;
    
    /**
     * @var array
     */
    public $option_groups;
    
    /**
     * Default constructor.
     * @param string $label
     * @param string $name
     * @param array $options
     * @param string|array $selected
     * @param string $description
     * @param string $placeholder
     * @param string $required
     * @param string $readonly
     * @param string $size
     */
    public function __construct(
        $label, $name, array $options=array(), $selected='', $description='', 
        $placeholder='', $required=false, $readonly=false, $size=0
    )
    {
        parent::__construct(
            $label, $name, $selected, $description, $placeholder, 
            FormFieldType::TEXT, $required, $readonly, $size
        );
        
        $this->options = $options;
        
        $this->option_groups = array();
    }
    
    /**
     * Add Option to be listed on select box.
     * @param string $label
     * @param label $value
     */
    public function AddOption($label, $value)
    {
        $this->options[$label] = $value;
    }
    
    /**
     * Add an options group
     * @param label $group_label
     * @param array $options Example: array("label"=>"value", ...)
     */
    public function AddOptionsGroup($group_label, $options)
    {
       $this->option_groups[$group_label] = $options;
    }
    
    /**
     * Set single or multiple selected values.
     * @param string|array $values
     */
    public function SetSelected($values)
    {
        $this->value = $values;
    }
    
    /**
     * @todo Add jquery plugin to dinamycally show characters left when size > 0.
     */
    public function GetHtml()
    {   
        $request_value = $this->GetRequestValue();
        
        $html = '<select ';
        $html .= 'id="'.$this->id.'" ';
        $html .= 'name="'.$this->name.'" ';
        
        if($this->IsArray())
            $html .= 'multiple ';
        
        if($this->placeholder)
            $html .= 'placeholder="'.$this->placeholder.'" ';
        
        if($this->required > 0)
            $html .= 'required ';
        
        if($this->readonly > 0)
            $html .= 'readonly ';
        
        if(count($this->attributes) > 0)
        {
            foreach($this->attributes as $name=>$value)
            {
                $html .= $name.'="'.$value.'" ';
            }
        }
        
        $html .= '>' . "\n";
        
        foreach($this->option_groups as $label=>$options)
        {
            $html .= '<optgroup label="'.$label.'">' . "\n";
            foreach($options as $option_label=>$option_value)
            {
                $html .= '<option value="'.$option_value.'" ';

                if($this->IsArray())
                {   
                    if(is_array($request_value))
                    {
                        if(in_array($option_value, $request_value))
                            $html .= 'selected ';
                    }
                    elseif(is_array($this->value))
                    {
                        if(in_array($option_value, $this->value))
                            $html .= 'selected ';
                    }
                }
                else
                {
                    if(trim($request_value))
                    {
                        if($request_value == $option_value)
                            $html .= 'selected ';
                    }
                    elseif(trim($this->value) != '')
                    {
                        if($this->value == $option_value)
                            $html .= 'selected ';
                    }
                }
                
                $html .= '>';
                
                $html .= $option_label;
                
                $html .= '</option>' . "\n";
            }
            $html .= '</optgroup>' . "\n";
        }
        
        foreach($this->options as $label=>$value)
        {
            $html .= '<option value="'.$value.'" ';

            if($this->IsArray())
            {   
                if(is_array($request_value))
                {
                    if(in_array($value, $request_value))
                        $html .= 'selected ';
                }
                elseif(is_array($this->value))
                {
                    if(in_array($value, $this->value))
                        $html .= 'selected ';
                }
            }
            else
            {
                if(trim($request_value) != '')
                {
                    if($request_value == $value)
                        $html .= 'selected ';
                }
                elseif(trim($this->value) != '')
                {
                    if($this->value == $value)
                        $html .= 'selected ';
                }
            }

            $html .= '>';

            $html .= $label;

            $html .= '</option>' . "\n";
        }
        
        $html .= '</select>' . "\n";
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
}
?>
