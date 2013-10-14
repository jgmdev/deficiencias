<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Reports Statistics')?>
    field;
    
    field: content
    <?php
        Cms\Authentication::ProtectPage(Deficiencies\Permissions::ADMINISTRATOR);
                
        use Cms\Enumerations\FieldType;
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-deficiencies-stats">' . "\n";
        
        $form = new Cms\Form('admin-deficiencies', null, Cms\Enumerations\FormMethod::GET);
        
        $filter_group = new Cms\Form\FieldsGroup(t('Filters'), '', true);
        
        $cities = Deficiencies\Towns::GetAll();
        $cities = array(t('All')=>'') + $cities;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('City'), 'city', $cities
        ));
        
        $form->Render();

        $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');

        if(trim($_REQUEST['city']))
        {
            $select_count->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
        }

        $db = Cms\System::GetRelationalDatabase();

        //Count results
        $db->Count($select_count);
        
        print '</div>' . "\n";
        print '</div">' . "\n";
    ?>
    field;
    
row;