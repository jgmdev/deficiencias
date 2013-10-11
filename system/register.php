<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Create Account')?>
    field;
    
    field: content
    <?php
        if(Cms\Authentication::IsUserLogged())
            Cms\Uri::Go('login');
        
        //TODO: Temporary disabled
        /*if(!Cms\System::GetSiteSettings()->Get("new_registrations"))
        {
            Cms\Theme::AddMessage(
                t('Registrations are disabled, sorry for any inconvinience.'), 
                Cms\Enumerations\MessageType::ERROR
            );

            Cms\Uri::Go('');
        }*/
            
        $form = new Cms\Form('register', null, Cms\Enumerations\FormMethod::POST);
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data)
        {   
            if($_REQUEST['password'] == $_REQUEST['password_confirm'])
            {
                if(Cms\Users::Exists($_REQUEST['username']))
                {
                    Cms\Theme::AddMessage(
                        t('The username you provided has already been taken.'), 
                        Cms\Enumerations\MessageType::ERROR
                    );
                    return;
                }
                
                if(Cms\Users::EmailTaken($_REQUEST['email']))
                {
                    Cms\Theme::AddMessage(
                        t('The e-mail you provided has already been taken.'), 
                        Cms\Enumerations\MessageType::ERROR
                    );
                    return;
                }
                
                $user = new Cms\Data\User();
                $user->username = $_REQUEST['username'];
                $user->password = $_REQUEST['password'];
                $user->email = $_REQUEST['email'];
                $user->status = Cms\Enumerations\UserStatus::ACTIVE;
                $user->group = 'regular';
                
                Cms\Users::Add($user);
                
                Cms\Authentication::Login($_REQUEST['username'], $_REQUEST['password']);
                
                Cms\Uri::Go('login');
            }
            else
            {
                Cms\Theme::AddMessage(
                    t('Password and Confirm Password doesn\'t match.'), 
                    Cms\Enumerations\MessageType::ERROR
                );
            }
        });
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT_ERROR, function($signal_data)
        {
            if(isset($signal_data->validation_errors['username']))
            {
                $errors = $signal_data->validation_errors['username'];
                
                if(isset($errors[Cms\Enumerations\ValidatorError::MIN_LENGHT]))
                {
                    Cms\Theme::AddMessage(
                        t('The username should be at least 3 characters long'),
                        Cms\Enumerations\MessageType::ERROR
                    );
                }
                elseif(isset($errors[Cms\Enumerations\ValidatorError::PATTERN]))
                {
                    Cms\Theme::AddMessage(
                        t('The username can only contain characters from a-z, 0-9, - and .'),
                        Cms\Enumerations\MessageType::ERROR
                    );
                }
            }
        });
        
        $username = new Cms\Form\Field\Text(
            'Username', 'username', '', '', '', true
        );
        $username->SetValidator(new Cms\Form\Validator\Username);
        
        $form->AddField($username);
       
        $form->AddField(new Cms\Form\Field\Password(
            'Password', 'password', '', '', '', true
        ));
        
        $form->AddField(new Cms\Form\Field\Password(
            'Confirm Password', 'password_confirm', '', '', '', true
        ));
        
        $email_validator = new Cms\Form\Validator\Email;
        $email_validator->SetErrorMessage(t('Please provide a valid e-mail address.'));
        $email = new Cms\Form\Field\Text('E-mail', 'email', '', '', '', true);
        $email->SetValidator($email_validator);
        
        $form->AddField($email);
        
        $form->AddField(new Cms\Form\Field\Submit(t('Register'), 'register'));
        
        $form->Render();
    ?>
    field;
    
row;