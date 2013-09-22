<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

/**
 * Form generator. 
 * Sends signals FormSignal::SUBMIT and FormSignal::SUBMIT_ERROR.
 */
class Form extends Signals\Signal
{
    /**
     * Unique identifier of form.
     * @var string
     */
    public $id;
    
    /**
     * Unique name of form.
     * @var string
     */
    public $name;
    
    /**
     * Url that will receive the form data on submit.
     * @var string
     */
    public $action;
    
    /**
     * @see \Cms\Enumerations\FormMethod
     * @var string
     */
    public $method;
    
    /**
     * List of all fields added to the form, including those
     * from individual groups.
     * @var \Cms\Form\Field[]
     */
    public $fields;
    
    /**
     * @var \Cms\Form\FieldsGroup[]
     */
    public $groups;
    
    /**
     * @var string
     */
    public $encoding;
    
    /**
     * Stores a reference to fields and groups in the order they where added.
     * @var array
     */
    public $elements;
    
    /**
     * Default constructor.
     * @param string $name
     * @param string $action
     * @param string $method
     * @param string $encoding
     * @return \Cms\Form
     */
    public function __construct($name, $action=null, $method="GET", $encoding=null)
    {
        $this->name = $name;
        $this->id = $name;
        
        if(!$action)
            $this->action = Uri::GetUrl(Uri::GetCurrent());
        else
            $this->action = $action;
        
        $this->method = strtoupper($method);
        $this->encoding = $encoding;
        
        return $this;
    }
    
    /**
     * Add a new field to the form.
     * @param \Cms\Form\Field $field
     * @return \Cms\Form
     */
    public function AddField(\Cms\Form\Field $field)
    {
        if($field->type == Enumerations\FormFieldType::FILE)
            $this->encoding = 'multipart/form-data';
        
        $this->fields[] = $field;
        
        $this->elements[] = $field;
        
        return $this;
    }
    
    /**
     * Add a group of fields to the form.
     * @param \Cms\Form\FieldsGroup $group
     * @throws \Cms\Exceptions\Form\EmptyFieldsGroupException
     */
    public function AddGroup(\Cms\Form\FieldsGroup $group)
    {
        if(count($group->fields) < 0)
            throw new Exceptions\Form\EmptyFieldsGroupException;

        if($this->encoding != 'multipart/form-data')
        {
            foreach($group->fields as $field)
            {
                if($field->type == Enumerations\FormFieldType::FILE)
                {
                    $this->encoding = 'multipart/form-data';
                    break;
                }
            }
        }
        
        foreach($group->fields as &$field)
        {
            $this->fields[] = $field;
        }
        
        $this->groups[] = $group;
        
        $this->elements[] = $group;
    }
    
    /**
     * Generate the form html.
     * @return string
     */
    public function GetHtml()
    {
        $html = '';
        
        if(count($this->elements) > 0)
        {
            $html .= '<form id="'.$this->id.'" ';
            $html .= 'name="'.$this->name.'" ';
            $html .= 'action="'.Uri::GetUrl($this->action).'" ';
            $html .= 'method="'.$this->method.'" ';
            
            if($this->encoding)
                $html .= 'encoding="'.$this->encoding.'" ';
            
            $html .= '>' . "\n";
            
            foreach($this->elements as $element)
            {
                if($element instanceof \Cms\Form\FieldsGroup)
                {
                    /* @var $group \Cms\Form\FieldsGroup */
                    $group = $element;
                    
                    if($group->collapsed)
                    {
                        $html .= '<fieldset class="collapsible collapsed">' . "\n";
                        $html .= '<legend><a class="expand" href="javascript:void(0)">'.$group->label.'</a></legend>' . "\n";
                    }
                    else
                    {
                        $html .= '<fieldset class="collapsible">' . "\n";
                        $html .= '<legend><a class="collapse" href="javascript:void(0)">'.$group->label.'</a></legend>' . "\n";
                    }

                    $form .= $legend;
                    
                    foreach($group->fields as $field)
                    {
                        $field->id = $this->id . '_' . $field->id;
                        
                        $html .= $field->GetLabelHtml();
                        $html .= $field->GetHtml();
                    }
                    
                    if($group->description)
                    {
                        $html .= '<p class="description">'.$group->description.'</p>';
                    }
                    
                    $html .= '</fieldset>' . "\n";
                }
                elseif($element instanceof \Cms\Form\Field)
                {   
                    /* @var $field \Cms\Form\Field */
                    $field = $element;
                    
                    $field->id = $this->id . '_' . $field->id;
                    
                    $html .= $field->GetLabelHtml();
                    $html .= $field->GetHtml();
                }
            }
            
            $html .= '</form>' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Print the form html generated by GetHTML() and validates
     * form if it was submitted, also sends the SUBMIT or SUBMIT_ERROR
     * signals.
     */
    public function Render()
    {
        $this->ValidateFormOnSubmit();
        
        print $this->GetHtml();
    }
    
    /**
     * Checks if the form data was submitted.
     * @return boolean
     */
    public function CheckIfSubmit()
    {
        if(count($this->fields) > 0)
        {
            foreach($this->fields as $field)
            {
                if($field->type != Enumerations\FormFieldType::SUBMIT)
                    continue;
                
                if($this->method == Enumerations\FormMethod::POST)
                {
                    if(isset($_POST[$field->name]))
                    {
                        return true;
                    }
                }
                elseif($this->method == Enumerations\FormMethod::GET)
                {
                    if(isset($_GET[$field->name]))
                    {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    protected function ValidateFormOnSubmit()
    {
        if(!$this->CheckIfSubmit())
            return;
        
        if(count($this->fields) > 0)
        {
            $errors = false;
            $validation_errors = array();
            
            foreach($this->fields as $field)
            {
                if($field->required)
                {
                    if(!isset($_REQUEST[$field->name]) || trim($_REQUEST[$field->name]) == '')
                    {
                        $message = str_replace(
                            '{field}', 
                            $field->label, 
                            t('Please provide the {field} field.')
                        );
                        
                        Theme::AddMessage($message, Enumerations\MessageType::ERROR);
                        
                        $errors = true;
                        
                        continue;
                    }
                }
                
                if($field->size > 0)
                {
                    if(strlen($_REQUEST[$field->name]) > $field->size)
                    {
                        $message = str_replace(
                            array('{field}', '{size}'), 
                            array($field->label, $field->size), 
                            t('The lenght of {field} exceeds the maximum allowed of {size}.')
                        );
                        
                        Theme::AddMessage($message, Enumerations\MessageType::ERROR);
                        
                        $errors = true;
                        
                        continue;
                    }
                }
                
                if($field->readonly)
                {
                    if($_REQUEST[$field->name] != $field->value)
                    {
                        $message = str_replace(
                            '{field}', 
                            $field->label, 
                            t('{field} is read only and you injected a different value than its original.')
                        );
                        
                        Theme::AddMessage($message, Enumerations\MessageType::ERROR);
                        
                        $errors = true;
                        
                        continue;
                    }
                }
                
                if(!$field->HasValidValue())
                {
                    $errors = true;
                    
                    $validation_errors[$field->name] = $field->validator->errors;
                }
            }
            
            //Prepare signal data
            $signal_data = new Signals\SignalData;
            $signal_data->Add('form', $this);
                
            if(!$errors)
            {   
                //Local signal
                $this->Send(Signals\Type\FormSignal::SUBMIT, $signal_data);
                
                //Global signal
                Signals\SignalHandler::Send(Signals\Type\FormSignal::SUBMIT, $signal_data);
            }
            else
            {
                $signal_data->Add('validation_errors', $validation_errors);
                
                //Local signal
                $this->Send(Signals\Type\FormSignal::SUBMIT_ERROR, $signal_data);
                
                //Global signal
                Signals\SignalHandler::Send(Signals\Type\FormSignal::SUBMIT_ERROR, $signal_data);
            }
        }
    }
}
?>
