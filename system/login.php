<?php exit; ?>

row: 0

    field: title
        <?=t('My Account')?>
    field;
    
    field: content
    <?php
        $form = new Cms\Form('login', null, Cms\Enumerations\FormMethod::POST);
        
        $form->Listen(Cms\Signals\Type\FormSignal::SUBMIT, function($signal_data)
        {
            try
            {
                Cms\Authentication::Login($_REQUEST['username_email'], $_REQUEST['password']);
                
                Cms\Uri::Go('user');
            }
            catch(\Cms\Exceptions\Users\AwaitingApprovalException $e)
            {
                Cms\Theme::AddMessage(t('Your account is waiting for approval.'));
            }
            catch(Exception $e)
            {
                Cms\Theme::AddMessage(t('Invalid username, email or password.'));
            }
        });
        
        $form->AddField(new Cms\Form\TextField('Username or E-mail', 'username_email', '', '', '', true));
       
        $form->AddField(new Cms\Form\PasswordField('Password', 'password', '', '', '', true));
        
        $form->AddField(new Cms\Form\SubmitField(t('Login'), 'login'));
        
        $form->Render();
    ?>
    field;
    
row;