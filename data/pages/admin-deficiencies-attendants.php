<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t('Deficiency Attendants')?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(
            Cms\Enumerations\Permissions\Groups::VIEW
        );

        Cms\Theme::AddTab(t('Users'), 'admin/users');
        Cms\Theme::AddTab(t('Create Group'), 'admin/groups/add');

        $form = new \Cms\Form('deficiency-attendants');
        
        $groups = Cms\Groups::GetList();
        
        $options = array();
        foreach ($groups as $group)
        {
            $options[$group->name] = $group->machine_name;
        }
        
        $form->AddField(new \Cms\Form\Field\CheckBox(
            t('Groups'), 'groups[]', $options, $selected, 
            t('Select the groups that will have a role of attendant. This group users will be displayed on the assign to field.'), 
            true, false
        ));
    ?>
    field;
row;
