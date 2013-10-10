<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t("Delete Group")?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Groups::DELETE);

        Cms\Theme::AddTab(t('Edit'), 'admin/groups/edit', array(
            'group'=>$_REQUEST['group']
        ));
        
        $form = new Cms\Form('delete-group');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            if(isset($_REQUEST['btnYes']))
            {
                try
                {
                    Cms\Groups::Delete($_REQUEST['group']);
                    
                    Cms\Theme::AddMessage(t('Group successfully deleted.'));
                }
                catch(Exception $e)
                {
                    Cms\Theme::AddMessage(
                        $e->getMessage(), 
                        Cms\Enumerations\MessageType::ERROR
                    );
                }

                Cms\Uri::Go('admin/groups');
            }
            elseif(isset($_REQUEST['btnNo']))
            {
                Cms\Uri::Go('admin/groups', array(
                    'group'=>$_REQUEST['group']
                ));
            }
            
        });
        
        $html = '<div class="cmsgui">' .
            '<div class="admin-groups-delete">' . 
            '<p>' .
            t('This action will also delete all group settings.') . ' ' . 
            t('Are you sure you want to delete the group?') .
            '</p>' .
            '<div><strong>'.t("Group:").' '.$_REQUEST["group"].'</strong></div>' .
            '</div></div>' . "\n"
        ;
        
        $form->AddField(new Cms\Form\Field\Custom($html));
        
        $form->AddField(new Cms\Form\Field\Hidden('group', $_REQUEST['group']));
        
        $form->AddField(new Cms\Form\Field\Submit(t('Yes'), 'btnYes'));

        $form->AddField(new Cms\Form\Field\Submit(t('No'), 'btnNo'));
        
        $form->Render();
    ?>
    field;
row;
