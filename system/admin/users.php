<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Users')?>
    field;
    
    field: content
    <?php
        Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Users::VIEW);
        
        Cms\Theme::AddTab(t('List View'), 'admin/users');
        Cms\Theme::AddTab(t('Navigation View'), 'admin/users/navigation');
        Cms\Theme::AddTab(t('Create User'), 'admin/users/add');
        Cms\Theme::AddTab(t('Groups'), 'admin/groups');
        Cms\Theme::AddTab(t('Export'), 'admin/users/export');
        
        $users_select = new Cms\DBAL\Query\Select('users');
        $users_select->SelectAll();
        
        $users_count = new Cms\DBAL\Query\Count('users', 'username', 'users_count');
        
        $page = 1;
			
        if(isset($_REQUEST['page']))
        {
            $page = intval($_REQUEST['page']);
        }

        if(trim($_REQUEST['group']) != '')
        {
            $is_regular = $_REQUEST['group']=='regular' ? true : false;

            $users_select->WhereEqual(
                'user_group', $_REQUEST['group'], 
                Cms\Enumerations\FieldType::TEXT
            );
            
            $users_count->WhereEqual(
                'user_group', $_REQUEST['group'], 
                Cms\Enumerations\FieldType::TEXT
            );
            
            if($is_regular)
            {
                $users_select->OrOp()->WhereEqual(
                    'user_group', '', 
                    Cms\Enumerations\FieldType::TEXT
                );
                
                $users_count->OrOp()->WhereEqual(
                    'user_group', '', 
                    Cms\Enumerations\FieldType::TEXT
                );
            }
        }

        if(trim($_REQUEST["status"]) != "")
        {
            if($users_select->HasWhere())
            {
                $users_select->AndOp()->WhereEqual(
                    'status', $_REQUEST['status'], 
                    Cms\Enumerations\FieldType::TEXT
                );
                
                $users_count->AndOp()->WhereEqual(
                    'status', $_REQUEST['status'], 
                    Cms\Enumerations\FieldType::TEXT
                );
            }
            else
            {
                $users_select->WhereEqual(
                    'status', $_REQUEST['status'], 
                    Cms\Enumerations\FieldType::TEXT
                );
                
                $users_count->WhereEqual(
                    'status', $_REQUEST['status'], 
                    Cms\Enumerations\FieldType::TEXT
                );
            }
                
        }

        $groups_array = Cms\Groups::GetList();
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-users">' . "\n";

        print '<form method="get" action="' . Cms\Uri::GetUrl('admin/users') . '">' . "\n";

        print t('Filter view by group:') . ' <select name="group">'. "\n";
        print '<option value="">' . t('All') . '</option>' . "\n";
        foreach($groups_array as $group)
        {
            $selected = '';

            if($_REQUEST['group'] == $group->machine_name)
            {
                $selected = 'selected="selected"';
            }

            print "<option $selected value=\"{$group->machine_name}\">{$group->name}</option>\n";
        }
        print '</select>' . "\n";

        print t(' status:') . ' <select name="status">' . "\n";
        print '<option value="">' . t('All') . '</option>' . "\n";
        foreach(Cms\Enumerations\UserStatus::GetAll() as $status_id)
        {
            $selected = "";

            if(''.$_REQUEST['status'].'' == ''.$status_id.'')
            {
                $selected = 'selected="selected"';
            }
            
            $status_label = Cms\Enumerations\UserStatus::GetLabel($status_id);

            print "<option $selected value=\"$status_id\">$status_label</option>\n";
        }
        print '</select>' . "\n";

        print '<input type="submit" value="'.t('View').'" />';

        print '</form>' . "\n";

        $db = Cms\System::GetRelationalDatabase();
        
        $users_total = 0;
        
        if($db->Count($users_count))
            $users_total = $db->FetchArray()['users_count'];

        print '<h2>' . t('Total users:') . ' ' . $users_total . '</h2>';

        $users_select->OrderBy('username')
            ->Limit($page-1, 30)
        ;
        
        $db->Select($users_select);

        print Cms\Utilities::GenerateNavigation(
            $users_total, $page, 'admin/users', "", 30, array(
                "group"=>$_REQUEST["group"]
            )
        );

        print '<table class="list">' . "\n";
        print '<thead>' . "\n";
        print '<tr>' . "\n";
        print '<td class="username">' . t('Username') . '</td>' . "\n";
        print '<td class="email">' . t('E-mail') . '</td>' . "\n";
        print '<td class="status">' . t('Status') . '</td>' . "\n";
        print '<td class="register-date">' . t('Register date') . '</td>' . "\n";
        print '<td class="operation">' . t('Operation') . '</td>' . "\n";
        print '</tr>' . "\n";
        print '</thead>' . "\n";

        print '<tbody>' . "\n";
        while($user_row = $db->FetchArray())
        {
            $username = $user_row['username'];
            
            $user_data = Cms\Users::GetData($username);

            print '<tr>';

            print '<td class="username">' . $username . '</td>';

            print '<td class="email">' . $user_data->email . '</td>';

            print '<td class="status">' . Cms\Enumerations\UserStatus::GetLabel($user_data->status) . '</td>';

            print '<td class="register-date">' . date('m/d/Y g:i:s a', intval($user_data->registration_date)) . '</td>';

            $edit_url = Cms\Uri::GetUrl('account/profile', array('username'=>$username));
            $delete_url = Cms\Uri::GetUrl('admin/users/delete', array('username'=>$username));

            print '<td class="operation">' . 
            "<a class=\"edit\" href=\"$edit_url\">" . t('Edit') . '</a> ' .
            "<a class=\"delete\" href=\"$delete_url\">" . t('Delete') . '</a>' .					
            '</td>';

            print '</tr>';
        }
        print '</tbody>' . "\n";

        print '</table>';

        print Cms\Utilities::GenerateNavigation(
            $users_total, $page, 'admin/users', "", 30, array(
                "group"=>$_REQUEST["group"]
            )
        );
        
        print '</div>' . "\n"; //End admin-users
        print '</div>' . "\n"; //End cmsgui
    ?>
    field;
    
row;