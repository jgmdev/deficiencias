<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t("Delete User")?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Users::DELETE);

        \Cms\Theme::AddTab(t('Edit'), 'account/profile', array(
            'username'=>$_REQUEST['username']
        ));
        
        $form = new Cms\Form('delete-user');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            if(isset($_REQUEST['btnYes']))
            {
                try
                {
                    Cms\Users::Delete($_REQUEST['username']);
                    
                    Cms\Theme::AddMessage(t('User successfully deleted.'));
                }
                catch(Exception $e)
                {
                    Cms\Theme::AddMessage(
                        $e->getMessage(), 
                        Cms\Enumerations\MessageType::ERROR
                    );
                }

                Cms\Uri::Go('admin/users');
            }
            elseif(isset($_REQUEST['btnNo']))
            {
                Cms\Uri::Go('account/profile', array(
                    'username'=>$_REQUEST['username']
                ));
            }
            
        });
        
        $html = '<div class="cmsgui">' .
            '<p>' .
            t('This action will also delete all users content.') . ' ' . 
            t('Are you sure you want to delete the user?') .
            '</p>' .
            '<div><strong>'.t("Username:").' '.$_REQUEST["username"].'</strong></div>' .
            '</div>' . "\n"
        ;
        
        $form->AddField(new Cms\Form\Field\Custom($html));
        
        $form->AddField(new Cms\Form\Field\Hidden('username', $_REQUEST['username']));
        
        $form->AddField(new Cms\Form\Field\Submit(t('Yes'), 'btnYes'));

        $form->AddField(new Cms\Form\Field\Submit(t('No'), 'btnNo'));
        
        $form->Render();
    ?>
    field;
row;
