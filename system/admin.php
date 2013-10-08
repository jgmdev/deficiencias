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
        <?=t('Control Center')?>
    field;

    field: content
    <?php
        use Cms\Data;
        use Cms\Enumerations\Permissions;
        
        if(!Cms\Authentication::IsUserLogged())
            Cms\Authentication::ProtectPage();

        $page_groups = new Data\PagesGroupList;
        
        // Users
        $users_view = new Data\Page('admin/users');
        $users_view->title = t('View');
        $users_view->description = t('View the existing users on the system.');
        $users_view->AddPermission(Permissions\Users::VIEW);
                
        $users_add = new Data\Page('admin/users/add');
        $users_add->title = t('Add');
        $users_add->description = t('Create new user account.');
        $users_add->AddPermission(Permissions\Users::CREATE);
        
        $page_groups->AddGroup(t('Users'), array(
           $users_view, $users_add 
        ));
        
        // Groups
        $groups_view = new Data\Page('admin/groups');
        $groups_view->title = t('View');
        $groups_view->description = t('View the existing groups on the system.');
        $groups_view->AddPermission(Permissions\Groups::VIEW);
                
        $groups_add = new Data\Page('admin/groups/add');
        $groups_add->title = t('Add');
        $groups_add->description = t('Create new user account.');
        $groups_add->AddPermission(Permissions\Groups::CREATE);
        
        $page_groups->AddGroup(t('Groups'), array(
           $groups_view, $groups_add 
        ));
        
        // Send Control Center page generation signal
        $signal_data = new Cms\Signals\SignalData;
        $signal_data->Add('page_groups', $page_groups);
        
        Cms\Signals\SignalHandler::Send(
            Cms\Enumerations\Signals\Gui::GENERATE_CONTROL_CENTER, 
            $signal_data
        );
        
        // Generate admin page
        $groups = $page_groups->GetPermittedGroups();
        
        if(count($groups) <= 0)
        {
            Cms\Theme::AddMessage(t('No task assigned to you on the control center.'));
            Cms\Uri::Go('account');
        }
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin">' . "\n";
        
        foreach($groups as $group_name=>$pages)
        {
            print '<div class="group">' . "\n";
            
            print '<h2>' . $group_name . '</h2>' . "\n";
            
            print '<div class="links">' . "\n";
            
            foreach($pages as $page_uri=>$page)
            {
                print '<a href="'.Cms\Uri::GetUrl($page_uri).'">';
                print $page->title;
                print '</a>' . "\n";
                
                print '<div class="description">';
                print $page->description;
                print '</div>' . "\n";
            }
            
            print '</div>' . "\n";
            
            print '</div>' . "\n";
        }
        
        print '</div>' . "\n";
        print '</div>' . "\n";
    ?>
    field;
row;
