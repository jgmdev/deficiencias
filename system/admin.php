<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0
    field: title
        <?=t('Control Center')?>
    field;

    field: content
    <?php
        if(!Cms\Authentication::IsUserLogged())
            Cms\Authentication::ProtectPage();
        
        // Generate admin page
        $groups = Cms\Pages::GetAdminPageGroups();
        
        if(count($groups) <= 0)
        {
            Cms\Theme::AddMessage(t('No task assigned to you on the control center.'));
            Cms\Uri::Go('account');
        }
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin">' . "\n";
        
        foreach($groups as $group_name=>$pages)
        {
            print '<div class="section">' . "\n";
            
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
