<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

/**
 * Form generator.
 * Sends signals Signals\Form::SUBMIT and Signals\Form::SUBMIT_ERROR.
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
     * Reference set to $_POST when form is submitted and successfully validated.
     * @var array
     */
    public $post;

    /**
     * Reference set to $_GET when form is submitted and successfully validated.
     * @var array
     */
    public $get;

    /**
     * Reference set to $_REQUEST when form is submitted and successfully validated.
     * @var array
     */
    public $request;
    
    /**
     * A global filter applied to all fields value.
     * @var \Cms\Form\Filter
     */
    public $filter;

    /**
     * Default constructor.
     * @param string $name
     * @param string $action
     * @param string $method
     * @param string $encoding
     * @return \Cms\Form
     */
    public function __construct($name, $action=null, $method="POST", $encoding=null)
    {
        $this->name = $name;
        $this->id = $name;

        if(!$action)
            $this->action = Uri::GetUrl(Uri::GetCurrent());
        else
            $this->action = $action;

        $this->method = strtoupper($method);
        $this->encoding = $encoding;

        $this->post = null;
        $this->get = null;
        $this->request = null;

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
                    //Scripts that adds collapse capabilities for fieldsets.
                    Theme::AddScript("scripts/forms.js");

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
                        $html .= '<legend><a class="collapse" style="cursor: pointer">'.$group->label.'</a></legend>' . "\n";
                    }

                    $form .= $legend;

                    foreach($group->fields as $field)
                    {
                        $field->id = $this->id . '-' . $field->id;

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

                    $field->id = $this->id . '-' . $field->id;

                    $html .= $field->GetLabelHtml();
                    $html .= $field->GetHtml();
                }
            }

            $html .= '</form>' . "\n";
        }

        return $html;
    }
    
    /**
     * A filter that is applied to each field element value.
     * @param \Cms\Form\Filter $filter
     */
    public function SetGlobalFilter(\Cms\Form\Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Print the form html generated by GetHTML() and validates
     * form if it was submitted, also sends the SUBMIT or SUBMIT_ERROR
     * signals.
     */
    public function Render()
    {
        //Prepare signal data
        $signal_data = new Signals\SignalData;
        $signal_data->Add('form', $this);

        //Local signal
        $this->Send(Enumerations\Signals\Form::RENDER, $signal_data);

        //Global signal
        Signals\SignalHandler::Send(Enumerations\Signals\Form::RENDER, $signal_data);

        //If form was submitted validate it
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

    /**
     * Check all field values are valid when the form is submitted.
     */
    protected function ValidateFormOnSubmit()
    {
        if(!$this->CheckIfSubmit())
            return;
        
        $this->post = &$_POST;
        $this->get = &$_GET;
        $this->request = &$_REQUEST;

        if(count($this->fields) > 0)
        {
            $errors = false;
            $validation_errors = array();

            foreach($this->fields as $field)
            {
                $request_value = $field->GetRequestValue();

                //Check if required
                if($field->required)
                {
                    $is_blank = false;

                    if(
                        ($field->IsArray() && !is_array($request_value)) ||
                        ($field->IsArray() && count($request_value) <= 0) ||
                        $request_value == null
                    )
                    {
                        $is_blank = true;
                    }
                    elseif(!$field->IsArray())
                    {
                        if(!is_string($request_value))
                            $is_blank = true;
                        elseif(trim($request_value) == "")
                            $is_blank = true;
                    }

                    if($is_blank)
                    {
                        $message = str_replace(
                            '{field}',
                            ($field->label!=''?$field->label:$field->name),
                            t('Please provide the {field} field.')
                        );

                        Theme::AddMessage($message, Enumerations\MessageType::ERROR);

                        $errors = true;

                        continue;
                    }
                }

                if($field->size > 0)
                {
                    $is_longer = false;

                    if($field->IsArray())
                    {
                        if(is_array($request_value))
                        {
                            foreach($request_value as $value)
                            {
                                if(strlen($value) > $field->size)
                                {
                                    $is_longer = true;
                                    break;
                                }
                            }
                        }
                    }
                    else
                    {
                        if(is_string($request_value))
                        {
                            if(strlen($request_value) > $field->size)
                                $is_longer = true;
                        }
                        elseif(!is_null($request_value))
                            $is_longer = true;
                    }

                    if($is_longer)
                    {
                        $message = str_replace(
                            array('{field}', '{size}'),
                            array(
                                ($field->label!=''?$field->label:$field->name),
                                $field->size
                            ),
                            t('The lenght of {field} exceeds the maximum allowed of {size}.')
                        );

                        Theme::AddMessage($message, Enumerations\MessageType::ERROR);

                        $errors = true;

                        continue;
                    }
                }

                if($field->readonly)
                {
                    if($request_value != $field->value)
                    {
                        $message = str_replace(
                            '{field}',
                            ($field->label!=''?$field->label:$field->name),
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

                    $validation_errors[$field->GetRealName()] = $field->validator->errors;
                    
                    if($field->validator->error_message)
                        Theme::AddMessage(
                            $field->validator->error_message,
                            Enumerations\MessageType::ERROR
                        );
                }
            }
            
            //Filter field values
            if(!$errors)
            {
                if(count($this->fields) > 0)
                {
                    foreach($this->fields as $field)
                    {
                        if(is_object($this->filter))
                        {
                            $field_value =& $field->GetRequestValue();
                            
                            if(is_array($field_value))
                            {
                                foreach($field_value as &$value)
                                {
                                    $value = $this->filter->GetFiltered($value);
                                }
                            }
                            elseif(strlen(trim($field_value)) > 0)
                            {
                                if($field->name == 'status')
                                    print $field_value;
                                
                                $field_value = $this->filter->GetFiltered($field_value);
                            }
                        }
                        
                        $field->FilterValue();
                    }
                }
            }

            //Prepare signal data
            $signal_data = new Signals\SignalData;
            $signal_data->Add('form', $this);

            if(!$errors)
            {
                //Local signal
                $this->Send(Enumerations\Signals\Form::SUBMIT, $signal_data);

                //Global signal
                Signals\SignalHandler::Send(Enumerations\Signals\Form::SUBMIT, $signal_data);
            }
            else
            {
                $signal_data->Add('validation_errors', $validation_errors);

                //Local signal
                $this->Send(Enumerations\Signals\Form::SUBMIT_ERROR, $signal_data);

                //Global signal
                Signals\SignalHandler::Send(Enumerations\Signals\Form::SUBMIT_ERROR, $signal_data);
            }
        }
    }
}
?>
