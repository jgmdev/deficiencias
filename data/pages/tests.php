<?php exit; ?>

row: 0

    field: title
        File to make tests
    field;
    
    field: content
    <?php
        $form = new Cms\Form('login', null, Cms\Enumerations\FormMethod::GET);
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data){
            print $signal_data->form->name . ' ';
            print 'form was submitted!';
        });
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT_ERROR, function($signal_data){
            print "errors were detected.";
        });
        
        $form->AddField(new Cms\Form\Field\Text('Login', 'username'));
       
        $form->AddField(new Cms\Form\Field\Password('Password', 'password', '', '', '', true, false, 20));
        
        $form->AddField(new Cms\Form\Field\Radio(
            'Some Options', 
            'options', 
            array('num1'=>'num1', 'num2'=>'num2'), 
            'num2', 
            "Some number to choose."
        ));
        
        $form->AddField(new Cms\Form\Field\CheckBox(
            'Some Colors', 
            'checks[]', 
            array('blue'=>'1', 'yellow'=>'2', 'red'=>'3'), 
            '', 
            "Some color to choose.", true
        ));
        
        $group = new \Cms\Form\FieldsGroup('Details', 'The more detailed info of you.');
        $group->AddField(new Cms\Form\Field\Text('FirstName', 'name[first]'));
        $group->AddField(new Cms\Form\Field\Text('Last Name', 'name[last]'));
        
        $form->AddGroup($group);
        
        $form->AddField(new Cms\Form\Field\Text(
            'Attributes', 'attributes[]', '', "List of attributes that better describe you.", "",
            false, false, 100
        ));
        
        $form->AddField(new Cms\Form\Field\TextArea(
            'Description', 
            'description[]', 
            '', 
            'A description', 
            'Write something short about you', 
            false, 
            false, 
            200
        ));
        
        $select = new \Cms\Form\Field\Select('Gender', 'gender[sex]', array('Male'=>'male', 'Female'=>'female', 'Other'=>'other'));
        $select->AddOptionsGroup("Animal", array('Male'=>'a_male', 'Female'=>'a_female', 'Other'=>'a_other'));
        $select->required = true;
        
        
        $form->AddField($select);
        
        $form->AddField(new Cms\Form\Field(
            '', 'btnSend', 'Send', '', '', Cms\Enumerations\FormFieldType::SUBMIT)
        );
        
        $form->Render();
    ?>
    field;
    
row;