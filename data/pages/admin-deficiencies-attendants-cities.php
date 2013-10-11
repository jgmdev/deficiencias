<?php
/**
 * @author Jefferson González
 * @license MIT
 */
exit;
?>

row: 0
    field: title
        <?=t('Group Cities')?>
    field;

    field: content
    <?php
        Cms\Authentication::ProtectPage(
            Deficiencies\Permissions::ADMINISTRATOR
        );
        
        Cms\Theme::AddTab(t('All Attendants'), 'admin/deficiencies/attendants');
        
        $form = new Cms\Form('deficiency-attendant-cities');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            try
            {
                $current_cities = Deficiencies\Attendants::GetCities(
                    $_REQUEST['group']
                );

                $current_cities[] = $_REQUEST['city'];

                Deficiencies\Attendants::SetCities(
                    $_REQUEST['group'], 
                    $current_cities
                );

                Cms\Theme::AddMessage(
                    t('Changes successfully saved.')
                );
            }
            catch(Exception $e)
            {
                Cms\Theme::AddMessage(
                    $e->getMessage(),
                    Cms\Enumerations\MessageType::ERROR
                );
            }
        });
        
        if(isset($_REQUEST['remove']))
        {
            try
            {
                $current_cities = Deficiencies\Attendants::GetCities(
                    $_REQUEST['group']
                );

                unset(
                    $current_cities[array_search($_REQUEST['city'], $current_cities)]
                );

                Deficiencies\Attendants::SetCities(
                    $_REQUEST['group'], 
                    $current_cities
                );

                Cms\Theme::AddMessage(
                    t('Changes successfully saved.')
                );
            }
            catch(Exception $e)
            {
                Cms\Theme::AddMessage(
                    $e->getMessage(),
                    Cms\Enumerations\MessageType::ERROR
                );
            }
        }
        
        
        $form->AddField(new \Cms\Form\Field\Hidden('group', $_REQUEST['group']));
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Cities'), 'city', Deficiencies\Towns::GetAll(), '', 
            t('Select the cities this attendants group will have access to.'), 
            '', true, false
        ));
        
        $form->AddField(new Cms\Form\Field\Submit(t('Add'), 'btnAdd'));
        
        $group = Cms\Groups::GetData($_REQUEST['group']);
        
        print '<h2>'.t('Cities assigned to:').' '.$group->name.'</h2>';
        
        $form->Render();
        
        $cities = Deficiencies\Attendants::GetCities($_REQUEST['group']);
        $all_cities = array_flip(Deficiencies\Towns::GetAll());
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-deficiencies-attendants-cities">' . "\n";
        
        print '<table class="list">' . "\n";

        print '<thead>' . "\n";
        print '<tr>' . "\n";

        print '<td class="name">' . t('City') . '</td>' . "\n";
        print '<td class="operation">' . t('Operation') . '</td>' . "\n";

        print '</tr>' . "\n";
        print '</thead>' . "\n";

        print '<tbody>' . "\n";
        foreach ($cities as $machine_name)
        {
            $city_name = $all_cities[$machine_name];
            
            print "<tr>\n";

            print '<td class="name">' . $city_name . '</td>' . "\n";
            
            $remove_url = Cms\Uri::GetUrl(
                'admin/deficiencies/attendants/cities', 
                array(
                    'group' => $_REQUEST['group'],
                    'city' => $machine_name,
                    'remove' => 1
                )
            );
            
            $remove_text = t('Remove');

            print '<td class="operation">' .
                '<a class="cities" href="'.$remove_url.'">'.$remove_text.'</a> ' .
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
