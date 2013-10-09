<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
    <?= t('Groups') ?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(
            Cms\Enumerations\Permissions\Groups::VIEW
        );

        Cms\Theme::AddTab(t('Users'), 'admin/users');
        Cms\Theme::AddTab(t('Create Group'), 'admin/groups/add');

        $groups = Cms\Groups::GetList();

        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-groups">' . "\n";
        
        print '<table class="list">' . "\n";

        print '<thead>' . "\n";
        print '<tr>' . "\n";

        print '<td>' . t('Name') . '</td>' . "\n";
        print '<td>' . t('Description') . '</td>' . "\n";
        print '<td>' . t('Operation') . '</td>' . "\n";

        print '</tr>' . "\n";
        print '</thead>' . "\n";

        print '<tbody>' . "\n";
        foreach ($groups as $group)
        {
            print "<tr>\n";

            print '<td>' . t($group->name) . '</td>' . "\n";
            print '<td>' . t($group->description) . '</td>' . "\n";

            $edit_url = Cms\Uri::GetUrl(
                'admin/groups/edit', 
                array('group' => $group->machine_name)
            );
            
            $permissions_url = Cms\Uri::GetUrl(
                'admin/groups/permissions', 
                array('group' => $group->machine_name)
            );
            
            $delete_url = Cms\Uri::GetUrl(
                'admin/groups/delete', 
                array('group' => $group->machine_name)
            );
            
            $edit_text = t('Edit');
            $permissions_text = t('Permissions');
            $delete_text = t('Delete');

            print '<td>' .
                '<a href="'.$edit_url.'">'.$edit_text.'</a>&nbsp;' .
                '<a href="'.$permissions_url.'">'.$permissions_text.'</a>&nbsp;' .
                '<a href="'.$delete_url.'">'.$delete_text.'</a>' .
                '</td>' . "\n"
            ;

            print '</tr>' . "\n";
        }
        print '</tbody>' . "\n";

        print '</table>' . "\n";
        
        print '</div>' . "\n";
        print '</div>' . "\n";
    ?>
    field;
row;
