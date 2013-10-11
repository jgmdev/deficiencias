<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

/**
 * Represents a form field.
 */
class Field
{
    /**
     * @see \Cms\Enumerations\FormFieldType
     * @var string
     */
    public $type;
    
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $value;
    
    /**
     * @var string
     */
    public $label;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var string
     */
    public $placeholder;
    
    /**
     * @var bool
     */
    public $required;
    
    /**
     * @var bool
     */
    public $readonly;
    
    /**
     * @var int
     */
    public $size;
    
    /**
     * @var array
     */
    public $attributes;
    
    /**
     * @var \Cms\Form\Validator
     */
    public $validator;
    
    /**
     * @var \Cms\Form\Filter
     */
    public $filter;
    
    /**
     * Default constructor.
     * @param string $label
     * @param string $name
     * @param string $value
     * @param string $description
     * @param string $placeholder
     * @param string $type
     * @param bool $required
     * @param bool $readonly
     * @param int $size
     * @return \Cms\Form\Field
     */
    public function __construct($label, $name, $value='', $description='', $placeholder='', $type=FormFieldType::TEXT, $required=false, $readonly=false, $size=0)
    {
        $this->attributes = array();
        
        $this->label = $label;
        $this->name = $name;
        $this->id = $this->GetRealName();
        $this->value = $value;
        $this->description = $description;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->size = $size;
        $this->validator = null;
        
        return $this;
    }
    
    /**
     * Add Attributes to the element when generating the html code.
     * @param string $name
     * @param string $value
     * @return \Cms\Form\Field
     */
    public function AddAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        
        return $this;
    }
    
    /**
     * Remove attribute.
     * @param string $name
     * @return \Cms\Form\Field
     */
    public function RemoveAttribute($name)
    {
        if(isset($this->attributes[$name]))
            unset($this->attributes[$name]);
        
        return $this;
    }
    
    /**
     * Check the field name to see if it was declared using array syntax,
     * example: field_name[]
     * @return boolean
     */
    public function IsArray()
    {
        $name = $this->name;

        $components = explode("[", $name);

        unset($components[0]);

        if(count($components) > 0)
        {
            $components_count = count($components);
            $current_component = 1;

            foreach($components as $component)
            {
                if($component == "]")
                {
                    if($components_count == $current_component)
                        return true;
                }

                $current_component++;
            }
        }
        
        return false;
    }
    
    /**
     * Set the validator for the element.
     * @param \Cms\Form\Validator $validator
     * @return \Cms\Form\Field
     */
    public function SetValidator(\Cms\Form\Validator $validator)
    {
        $this->validator = $validator;
        
        return $this;
    }
    
    /**
     * Set the value filter for the element.
     * @param \Cms\Form\Filter $filter
     * @return \Cms\Form\Field
     */
    public function SetFilter(\Cms\Form\Filter $filter)
    {
        $this->filter = $filter;
        
        return $this;
    }
    
    /**
     * Check if the current value read from $_REQUEST[name] or 
     * the value poperty matches the characteristics set by 
     * the validator.
     * @see SetValidator()
     * @return boolean
     */
    public function HasValidValue()
    {
        if(!is_object($this->validator))
            return true;
        
        $current_value = isset($_REQUEST[$this->GetRealName()]) ? 
            $this->GetRequestValue() : $this->value;
        
        if($this->IsArray())
        {
            if(is_array($current_value))
            {
                foreach($current_value as $value)
                {
                    if(!$this->validator->IsValid($value))
                        return false;
                }
            }
        }
        else
        {
            if(!$this->validator->IsValid($current_value))
                return false;
        }
        
        return true;
    }
    
    /**
     * Automatically filters the value send on a form submit and assings it
     * to the $_REQUEST global var.
     * @return string
     */
    public function FilterValue()
    {
        if(!is_object($this->filter) || !isset($_REQUEST[$this->GetRealName()]))
            return $this;
        
        $current_value =& $this->GetRequestValue();
        
        if($this->IsArray())
        {
            if(is_array($current_value))
            {
                foreach($current_value as &$value)
                {
                    $value = $this->filter->GetFiltered($value);
                }
            }
            
            return $this;
        }
        
        $current_value = $this->filter->GetFiltered($current_value);
    }
    
    /**
     * Generate the html code for the label of field.
     * @return string
     */
    public function GetLabelHtml()
    {
        if(!$this->label)
            return '';
        
        $html = '<label class="'.$this->type.'" for="'.$this->id.'">';
        $html .= $this->label;
        
        if($this->required)
            $html .= ' <span class="required">*</span>';
        
        $html .= '</label>' . "\n";
        
        return $html;
    }
    
    /**
     * Generate the html of field with description and some other stuff.
     * @return string
     */
    public function GetHtml()
    {
        $request_value = $this->GetRequestValue();
        
        $html = '';
        
        if($this->IsArray())
        {   
            $html .= '<div class="fields-container" id="'.$this->id.'-fields">' . "\n";
            
            $id = 0;
            $original_id = $this->id;
            
            if(is_array($request_value))
            {
                foreach($request_value as $value)
                {
                    if(trim($value))
                    {
                        $this->id .= '-' . $id;
                        $html .= '<div class="field">' . "\n";
                        $html .= $this->GetSingleHtml($value);
                        $html .= '<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]</a>' . "\n";
                        $html .= '</div>' . "\n";
                        $id++;
                        $this->id = $original_id;
                    }
                }
            }
            else
            {
                $this->id .= '-' . $id;
                $html .= '<div class="field">' . "\n";
                $html .= $this->GetSingleHtml();
                $html .= '<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]</a>' . "\n";
                $html .= '</div>' . "\n";
                $this->id = $original_id;
                $id++;
            }
            
            $html .= '<div class="count" style="display: none">'.($id-1).'</div>' . "\n";
            $html .= '</div>' . "\n";
            
            if($this->type != FormFieldType::HIDDEN)
            {
                $html .= "<hr />";

                $html .= '<a class="add-more" id="'.$this->id."-add".'" style="cursor: pointer; display: block; text-align: right;">Add More</a>';

                \Cms\Theme::AddRawScript(
                    '$(document).ready(function(){' . "\n" .
                    "\t" . '$("#'.$this->id.'-add").click(function(){' . "\n" .
                    "\t\t" . 'var element_id = \''.$this->id.'-\'+(parseInt($("#'.$this->id.'-fields .count").html()) + 1)+\'-limit\';' . "\n" .
                    "\t\t" . 'var container = $(\'<div class="field"></div>\')' . "\n" .
                    "\t\t" . 'var element = $(\''.trim($this->GetSingleHtml()).'\');' . "\n" .
                    "\t\t" . 'var remove = $(\'<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]&nbsp;</a>\')' . "\n" .
                    "\t\t" . 'element.attr("id", element_id);' . "\n" .
                    "\t\t" . '$("#'.$this->id.'-fields .count").html(parseInt($("#'.$this->id.'-fields .count").html()) + 1);' . "\n" .
                    "\t\t" . 'container.hide();' . "\n" .
                    "\t\t" . 'remove.css("display", "inline");' . "\n" .
                    "\t\t" . '$(container).append(element);' . "\n" .
                    "\t\t" . '$(container).append(remove);' . "\n" .
                    "\t\t" . '$("#'.$this->id.'-fields").append(container);' . "\n" .
                    "\t\t" . 'container.fadeIn("slow");' . "\n" .
                    "\t" . '});' . "\n" .
                    '});'
                );
            }
        }
        else
        {   
            if($request_value)
                $html .= $this->GetSingleHtml($request_value);
            else
                $html .= $this->GetSingleHtml($this->value);
        }
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
    
    /**
     * Generates only the html of the field element.
     * @param string $value
     * @return string
     */
    public function GetSingleHtml($value='')
    {
        $html = '<input type="'.$this->type.'" ';
        $html .= 'id="'.$this->id.'" ';
        $html .= 'name="'.$this->name.'" ';

        if(trim($value) != '')
            $html .= 'value="'.$value.'" ';
        
        if($this->placeholder)
            $html .= 'placeholder="'.$this->placeholder.'" ';
        
        if($this->required > 0)
            $html .= 'required ';
        
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
        
        return $html;
    }
    
    /**
     * Get name of field without array brackets. 
     * Example: name[] -> name
     * @return string
     */
    public function GetRealName()
    {
        $name = $this->name;

        $components = explode("[", $name);

        $real_name = $components[0];
        
        return $real_name;
    }
    
    /**
     * Get the value of the field sent when its parent form was submitted.
     * @return mixed|null
     */
    public function &GetRequestValue()
    {
        $name = $this->name;

        $components = explode("[", $name);

        $real_name = $components[0];

        unset($components[0]);

        if(!isset($_REQUEST[$real_name]))
            return null;
        
        $reference = &$_REQUEST[$real_name];

        if(count($components) > 0)
        {
            $components_count = count($components);
            $current_component = 1;

            foreach($components as $component)
            {
                if($component == "]")
                {
                    if($components_count == $current_component)
                        break;

                    if(isset($reference[0]))
                    {
                        if(is_array($reference[0]))
                        {
                            $reference = &$reference[0];
                        }
                        else
                        {
                            $reference = null;
                            break;
                        }
                    }
                    else
                    {
                        $reference = null;
                        break;
                    }
                }
                else
                {
                    $index = trim($component, "]");

                    if(isset($reference[$index]))
                    {
                        $reference = &$reference[$index];
                    }
                    else
                    {
                        $reference = null;
                        break;
                    }
                }

                $current_component++;
            }
        }
        
        return $reference;
    }
}
?>
