<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t('Group Permissions')?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Groups::EDIT);

        Cms\Theme::AddTab(t('Groups'), 'admin/groups');

        $form = new Cms\Form('group-permissions');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data)
        {
            if(isset($_REQUEST['btnSave']))
            {
                try
                {
                    $group = Cms\Groups::GetData($_REQUEST['group']);

                    /* @var $permissions_list \Cms\Data\PermissionsList */
                    foreach(Cms\Groups::GetPermissions($_REQUEST['group']) as $permissions_list)
                    {
                        $permissions = $permissions_list->GetAll();

                        /* @var $permission \Cms\Data\Permission */
                        foreach($permissions as $permission)
                        {   
                            if(isset($_REQUEST[$permission->identifier]))
                                $group->SetPermission($permission->identifier, true);
                            else
                                $group->SetPermission($permission->identifier, false);
                        }
                    }
                    
                    Cms\Theme::AddMessage(t('Permissions successfully set.'));
                    
                    Cms\Uri::Go('admin/groups');
                }
                catch(Exception $e)
                {
                    Cms\Theme::AddMessage(
                        $e->getMessage(),
                        Cms\Enumerations\MessageType::ERROR
                    );
                }
            }
            elseif(isset($_REQUEST['btnCancel']))
            {
                Cms\Uri::Go('admin/groups');
            }
        });
        
        $form->AddField(new Cms\Form\Field\Hidden('group', $_REQUEST['group']));

        /* @var $permissions_list \Cms\Data\PermissionsList */
        foreach(Cms\Groups::GetPermissions($_REQUEST['group']) as $permissions_list)
        {
            $field_group = new Cms\Form\FieldsGroup(
                $permissions_list->name, 
                $permissions_list->description,
                true
            );
            
            $permissions = $permissions_list->GetAll();
            
            /* @var $permission \Cms\Data\Permission */
            foreach($permissions as $permission)
            {   
                $field = new Cms\Form\Field\CheckBox(
                    $permission->label, $permission->identifier, 
                    array(''=>true), 
                    $permission->value, 
                    $permission->description
                );
                
                $field_group->AddField($field);
            }
            
            $form->AddGroup($field_group);
        }
        
        $form->AddField(new Cms\Form\Field\Submit(t('Save'), 'btnSave'));

        $form->AddField(new Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));
        
        $form->Render();
    ?>
    field;
row;
