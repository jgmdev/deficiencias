<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t('Edit Group')?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Groups::CREATE);

        $form = new Cms\Form('edit-group');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function($signal_data)
        {
            if(isset($_REQUEST['btnSave']))
            {
                $group = new Cms\Data\Group($_REQUEST['machine_name']);
                
                $group->name = $_REQUEST['name'];
                $group->description = $_REQUEST['description'];

                try
                {
                    Cms\Groups::Edit($_REQUEST['group'], $group);
                    
                    Cms\Theme::AddMessage(t('The group was successfully created.'));
                    
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
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT_ERROR, function($signal_data)
        {
            if(isset($signal_data->validation_errors['machine_name']))
            {
                $errors = $signal_data->validation_errors['machine_name'];
                
                if(isset($errors[Cms\Enumerations\ValidatorError::MIN_LENGHT]))
                {
                    Cms\Theme::AddMessage(
                        t('The machine name should be at least 3 characters long'),
                        Cms\Enumerations\MessageType::ERROR
                    );
                }
                elseif(isset($errors[Cms\Enumerations\ValidatorError::PATTERN]))
                {
                    Cms\Theme::AddMessage(
                        t('The machine name can only contain lowercase characters from a-z, and underscores.'),
                        Cms\Enumerations\MessageType::ERROR
                    );
                }
            }
        });
        
        $group_data = Cms\Groups::GetData($_REQUEST['group']);

        $form->AddField(new Cms\Form\Field\Text(
            t('Machine name'), 'machine_name', $_REQUEST['group'],
            t('A readable machine name, like for example: my-group.'), 
            '', true, true
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Name'), 'name', $group_data->name,
            t('A human readable name like for example: My Group.'), 
            '', true
        ));
        
        $form->AddField(new Cms\Form\Field\TextArea(
            t('Description'), 'description', $group_data->description,
            t('A brief description of the group.'), 
            '', true
        ));
        
        $form->AddField(new Cms\Form\Field\Submit(t('Save'), 'btnSave'));

        $form->AddField(new Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));
        
        $form->Render();
    ?>
    field;
row;
