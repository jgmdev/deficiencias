<?php exit; ?>

row: 0

    field: title
        <?=t('Create Account')?>
    field;
    
    field: content
    <?php
        $form = new Cms\Form('register', null, Cms\Enumerations\FormMethod::POST);
        
        $form->Listen(Cms\Signals\Type\FormSignal::SUBMIT, function($signal_data)
        {   
            if($_REQUEST['password'] == $_REQUEST['password_confirm'])
            {
                if(Cms\Users::Exists($_REQUEST['username']))
                {
                    Cms\Theme::AddMessage(t('The username you provided has already been taken.'), \Cms\Enumerations\MessageType::ERROR);
                    return;
                }
                
                if(Cms\Users::EmailTaken($_REQUEST['email']))
                {
                    Cms\Theme::AddMessage(t('The e-mail you provided has already been taken.'), \Cms\Enumerations\MessageType::ERROR);
                    return;
                }
                
                $user = new \Cms\Data\User();
                $user->username = $_REQUEST['username'];
                $user->password = $_REQUEST['password'];
                $user->group = 'regular';
                
                Cms\Users::Add($user);
                
                Cms\Theme::AddMessage(t('Your account was created, you can login now.'));
                
                Cms\Uri::Go('login');
            }
            else
            {
                Cms\Theme::AddMessage(t('Password and Confirm Password doesn\'t match.'), \Cms\Enumerations\MessageType::ERROR);
            }
        });
        
        $form->AddField(new Cms\Form\TextField('Username', 'username'));
       
        $form->AddField(new Cms\Form\PasswordField('Password', 'password', '', '', '', true));
        
        $form->AddField(new Cms\Form\PasswordField('Confirm Password', 'password_confirm', '', '', '', true));
        
        $email_validator = new \Cms\Form\Validator\EmailValidator;
        $email_validator->SetErrorMessage(t('Please provide a valid e-mail address.'));
        $email = new Cms\Form\TextField('E-mail', 'email', '', '', '', true);
        $email->SetValidator($email_validator);
        
        $form->AddField($email);
        
        $form->AddField(new Cms\Form\SubmitField(t('Register'), 'register'));
        
        $form->Render();
    ?>
    field;
    
row;