<?php exit; ?>

row: 0

    field: title
        File to make tests
    field;
    
    field: content
    <?php
        $form = new Cms\Form('login', null, Cms\Enumerations\FormMethod::POST);
        
        $form->Listen(Cms\Signals\Type\FormSignal::SUBMIT, function($signal_data){
            print $signal_data->form->name . ' ';
            print 'form was submitted!';
        });
        
        $form->AddField(new Cms\Form\TextField('Login', 'username'));
       
        $form->AddField(new Cms\Form\PasswordField('Password', 'password'));
        
        $form->AddField(new Cms\Form\TextAreaField(
            'Description', 
            'description', 
            '', 
            'A description', 
            'Write something short about you', 
            false, 
            false, 
            200
        ));
        
        $form->AddField(new Cms\Form\Field(
            '', 'btnSend', 'Send', '', '', Cms\Enumerations\FormFieldType::SUBMIT)
        );
        
        $form->Render();
    ?>
    field;
    
row;