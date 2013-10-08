<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('My Account')?>
    field;
    
    field: content
    <?php
        if(Cms\Authentication::IsUserLogged())
            Cms\Uri::Go('account');
        
        $form = new Cms\Form('login', null, Cms\Enumerations\FormMethod::POST);
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data)
        {
            try
            {
                Cms\Authentication::Login($_REQUEST['username_email'], $_REQUEST['password']);
                
                Cms\Uri::Go('account');
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
        
        $form->AddField(new Cms\Form\Field\Text('Username or E-mail', 'username_email', '', '', '', true));
       
        $form->AddField(new Cms\Form\Field\Password('Password', 'password', '', '', '', true));
        
        $form->AddField(new Cms\Form\Field\Submit(t('Login'), 'login'));
        
        $form->Render();
        
        //Forgot password link
        print "<div style=\"margin-top: 15px\">";
        print '<a href="' . Cms\Uri::GetUrl('forgot-password') . '">' . 
            t('Forgot Password?') .
        '</a>';
        
        print "</div>";
        
        //Register link
        if(Cms\System::GetSiteSettings()->Get('new_registrations'))
        {
            print '<h2>' . t('Create Account') . '</h2>';
            print '<a class="register-link" href="' . 
                Cms\Uri::GetUrl('register', array(
                    'return'=>$_REQUEST['return'])
                ) . '">' . 
                t('Register') . 
            "</a>";
            
            $benefits = Cms\System::GetSiteSettings()->Get('registration_benefits');
            
            if($benefits)
                print Cms\Utilities::PHPEval($benefits);
        }
    ?>
    field;
    
row;