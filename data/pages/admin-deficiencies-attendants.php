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
            Deficiencies\Permissions::ADMINISTRATOR
        );

        $groups = Deficiencies\Attendants::Get();

        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-deficiencies-attendants">' . "\n";
        
        print '<table class="list">' . "\n";

        print '<thead>' . "\n";
        print '<tr>' . "\n";

        print '<td class="name">' . t('Name') . '</td>' . "\n";
        print '<td class="description">' . t('Description') . '</td>' . "\n";
        print '<td class="operation">' . t('Operation') . '</td>' . "\n";

        print '</tr>' . "\n";
        print '</thead>' . "\n";

        print '<tbody>' . "\n";
        foreach ($groups as $machine_name)
        {
            $group = Cms\Groups::GetData($machine_name);
            
            print "<tr>\n";

            print '<td class="name">' . t($group->name) . '</td>' . "\n";
            print '<td class="description">' . t($group->description) . '</td>' . "\n";
            
            $cities_url = Cms\Uri::GetUrl(
                'admin/deficiencies/attendants/cities', 
                array('group' => $machine_name)
            );
            
            $cities_text = t('Assign Cities');

            print '<td class="operation">' .
                '<a class="cities" href="'.$cities_url.'">'.$cities_text.'</a> ' .
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
