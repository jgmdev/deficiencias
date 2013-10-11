<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Reported Deficiencies')?>
    field;
    
    field: content
    <?php
        use Cms\Enumerations\FieldType;
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="admin-deficiencies">' . "\n";
        
        $form = new Cms\Form('admin-deficiencies', null, Cms\Enumerations\FormMethod::GET);
        
        $cities = Deficiencies\Towns::GetAll();
        $cities = array(t('All')=>'') + $cities;
        
        $form->AddField(new Cms\Form\Field\Select(
            t('City'), 'city', $cities, '', '', 'City'
        ));
        
        $types = array_flip(Deficiencies\DeficiencyTypes::getAll());
        $types = array(t('All')=>'') + $types;
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Type'), 'type', $types, '', '', 'Type'
        ));
        
        $status = array_flip(Deficiencies\DeficiencyStatus::getAll());
        $status = array(t('All')=>'') + $status;
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Status'), 'status', $status, '', '', 'Status'
        ));
        
        $form->AddField(new \Cms\Form\Field\Submit('Filter', 'btnFilter'));
        
        $form->Render();

        $page = intval($_REQUEST['page']);

        if($page == 0)
            $page = 1;

        $select = new Cms\DBAL\Query\Select('deficiencies');
        $select->SelectAll();

        $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');

        if(trim($_REQUEST['city']))
        {
            $select->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
            $select_count->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
        }

        if(trim($_REQUEST['type']) != '')
        {
            if($select->HasWhere())
                $select->AndOp();

            $select->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);

            if($select_count->HasWhere())
                $select_count->AndOp();

            $select_count->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);
        }
        
        if(trim($_REQUEST['status']) != '')
        {
            if($select->HasWhere())
                $select->AndOp();

            $select->WhereEqual('status', $_REQUEST['status'], FieldType::INTEGER);

            if($select_count->HasWhere())
                $select_count->AndOp();

            $select_count->WhereEqual('status', $_REQUEST['status'], FieldType::INTEGER);
        }

        $limit_start = 0;

        if($page > 1)
            $limit_start = ($page-1) * 10;

        $select->Limit($limit_start, 10);

        $db = \Cms\System::GetRelationalDatabase();

        //Count results
        $db->Count($select_count);

        $result = $db->Fetch();

        $count = $result['reports_count'];

        //Return results
        $db->Select($select);

        $main_url = Cms\Uri::GetUrl('');
        $cities = array_flip(Deficiencies\Towns::GetAll());

        print '<div id="reports">';
        
        print '<h2>'.t('Total reports:').' '.$count.'</h2>';
        
        print '<table class="list">' . "\n";
        
        print '<thead>' . "\n";
        print '<tr>' . "\n";

        print '<td class="icon"></td>' . "\n";
        print '<td class="name">' . t('Address') . '</td>' . "\n";
        print '<td class="description">' . t('Status') . '</td>' . "\n";
        print '<td class="operation">' . t('Operation') . '</td>' . "\n";

        print '</tr>' . "\n";
        print '</thead>' . "\n";

        print '<tbody>' . "\n";
        while($report = $db->FetchArray())
        {
            print '<tr>';

            $style='background: transparent url('.$main_url.'/themes/deficiency/images/location.png) no-repeat center;';
                    
            switch($report['type']){
                case '0':
                    $style='background: transparent url('.$main_url.'/themes/deficiency/images/deficiency-hole.png) no-repeat center;';
                    break;
                case '1':
                    $style='background: transparent url('.$main_url.'/themes/deficiency/images/deficiency-broken-pipe.png) no-repeat center;';
                    break;
                case '3':
                    $style='background: transparent url('.$main_url.'/themes/deficiency/images/deficiency-traffic-lights.png) no-repeat center;';
                    break;
                case '4':
                    $style='background: transparent url('.$main_url.'/themes/deficiency/images/deficiency-rock-slide.png) no-repeat center;';
                    break;
                case '5':
                    $style='background: transparent url('.$main_url.'/themes/deficiency/images/deficiency-electric-pole.png) no-repeat center;';
                    break;
            }

            print '<td><a class="location" style="'.$style.'" href="reports/view?id='.$report['id'].'"></a></td>';

            print '<td class="details">';
            print '<a href="reports/view?id='.$report['id'].'">';
            print '<div class="route">'.$report['line1'].'</div>';
            print '<span class="city">';
            print $cities[$report['city']] . ', ' . 'PR';
            print '</span>';
            print '<div class="type">'.Deficiencies\DeficiencyTypes::getType($report['type']).'</div>';
            print '</a>';
            print '</td>';
            
            print '<td class="status">';
            print Deficiencies\DeficiencyStatus::getStatus($report['status']);
            print '</td>';
            
            print '<td class="operation">';
            print '<a class="edit" href="'.
                Cms\Uri::GetUrl('admin/deficiencies/edit', array('id'=>$report['id'])).'">'.
                t('Edit').
                '</a>';
            print '</td>';

            print '</tr>';
        }
        print '</tbody>' . "\n";

        print '</table>';
        
        print '</div>';

        print Cms\Utilities::GenerateNavigation(
            $count, $page, 'admin/deficiencies', '', 10,
            array(
                'city'=>$_REQUEST['city'],
                'type'=>$_REQUEST['type'],
                'status'=>$_REQUEST['status']
            )
        );
        
        print '</div>' . "\n";
        print '</div">' . "\n";
    ?>
    field;
    
row;