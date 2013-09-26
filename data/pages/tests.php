<?php exit; ?>

row: 0

    field: title
        File to make tests
    field;
    
    field: content
    <?php
        $form = new Cms\Form('login', null, Cms\Enumerations\FormMethod::GET);
        
        $form->Listen(Cms\Signals\Type\FormSignal::SUBMIT, function($signal_data){
            print $signal_data->form->name . ' ';
            print 'form was submitted!';
        });
        
        $form->Listen(Cms\Signals\Type\FormSignal::SUBMIT_ERROR, function($signal_data){
            print "errors were detected.";
        });
        
        $form->AddField(new Cms\Form\TextField('Login', 'username'));
       
        $form->AddField(new Cms\Form\PasswordField('Password', 'password', '', '', '', true, false, 20));
        
        $form->AddField(new Cms\Form\RadioField(
            'Some Options', 
            'options', 
            array('num1'=>'num1', 'num2'=>'num2'), 
            'num2', 
            "Some number to choose."
        ));
        
        $group = new \Cms\Form\FieldsGroup('Details', 'The more detailed info of you.');
        $group->AddField(new Cms\Form\TextField('FirstName', 'name[first]'));
        $group->AddField(new Cms\Form\TextField('Last Name', 'name[last]'));
        
        $form->AddGroup($group);
        
        $form->AddField(new Cms\Form\TextField(
            'Attributes', 'attributes[]', '', "List of attributes that better describe you.", "",
            false, false, 100
        ));
        
        $form->AddField(new Cms\Form\TextAreaField(
            'Description', 
            'description[]', 
            '', 
            'A description', 
            'Write something short about you', 
            false, 
            false, 
            200
        ));
        
        $select = new \Cms\Form\SelectField('Gender', 'gender[sex]', array('Male'=>'male', 'Female'=>'female', 'Other'=>'other'));
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