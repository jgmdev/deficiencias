<?php
/**
 * Copyright 2008, Jefferson Gonz�lez (JegoYalu.com)
 * This file is part of Jaris CMS and licensed under the GPLPP,
 * check the LICENSE.txt file for version and details or visit
 * http://gplpp.org/license.
 *
 * @file Page where users can reset their password.
 */
//For security the file content is skipped from the world eyes :)
exit;
?>

row: 0
    field: title
        <?=t('Forgot your password?')?>
    field;

    field: content
    <?php
        if(Cms\Authentication::IsUserLogged())
            Cms\Uri::Go('account');

        $form = new Cms\Form('forgot-password');

        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data){
            if(isset($_REQUEST['btnReset']))
            {
                $reset = false;

                if(isset($_REQUEST['username']))
                {
                    try
                    {
                        Cms\Users::ResetPasswordByUsername($_REQUEST['username']);
                        $reset = true;
                    }
                    catch(Cms\Exceptions\Users\UserNotExistsException $e){}
                }

                if(!$reset && isset($_REQUEST['email']) && $_REQUEST['email'] != '')
                {
                    try
                    {
                        Cms\Users::ResetPasswordByEmail($_REQUEST['username']);
                        $reset = true;
                    }
                    catch(Cms\Exceptions\Users\UserNotExistsException $e){}
                }

                if($reset)
                {
                    Cms\Theme::AddMessage(
                        t('Your password has been reset successfully. Check your e-mail inbox for details.')
                    );
                }
                else
                {
                    Cms\Theme::AddMessage(
                        t('The specified username or e-mail is invalid'),
                        Cms\Enumerations\MessageType::ERROR
                    );

                    Cms\Uri::Go('forgot-password');
                }

                Cms\Uri::Go('');
            }
            elseif(isset($_REQUEST['btnCancel']))
            {
                Cms\Uri::Go('');
            }
        });

        $form->AddField(new Cms\Form\Field\Text(
            t('Username'), 
            'username', 
            '', 
            t('If you remember your username write it down.')
        ));

        $form->AddField(new Cms\Form\Field\Custom('<h3>' . t('OR') . '</h3>'));

        $form->AddField(new Cms\Form\Field\Text(
            t('E-mail'), 
            'email', 
            '', 
            t('If you remember the e-mail that you used to register the account write it down.')
        ));

        $form->AddField(new Cms\Form\Field\Submit(t('Reset Password'), 'btnReset'));

        $form->AddField(new Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));

        $form->Render();
    ?>
    field;
row;
