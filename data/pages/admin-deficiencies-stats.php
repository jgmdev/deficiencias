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
        
        $filter_group->AddField(new Cms\Form\Field\Submit('Filter', 'btnFilter'));
        
        $form->AddGroup($filter_group);
        
        $form->Render();
        
        $types = Deficiencies\Types::getAll();
        $db = Cms\System::GetRelationalDatabase();
        
        foreach($types as $type_id=>$type_label)
        {
            $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'type_count');
            $select_count->WhereEqual('type', $type_id, FieldType::INTEGER);
            
            if(trim($_REQUEST['city']))
            {
                $select_count->AndOp()
                ->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
            }
            
            //Count results
            $db->Count($select_count);
            $total = $db->FetchArray()['type_count'];
            
            print '<h2>'.$type_label.' ('.$total.')</h2>';
            
            if($total <= 0)
                continue;
            
            $status = Deficiencies\Status::getAll();
            foreach($status as $status_id=>$status_label)
            {
                $status_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'status_count');
                $status_count->WhereEqual('status', $status_id, FieldType::INTEGER)
                    ->AndOp()
                    ->WhereEqual('type', $type_id, FieldType::INTEGER)
                ;
                
                if(trim($_REQUEST['city']))
                {
                    $status_count->AndOp()
                    ->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
                }
                
                $db->Count($status_count);
                
                $status_total = $db->FetchArray()['status_count'];
                
                print '<h3>'.$status_label.' ('.$status_total.')</h3>';
                
                if($status_total <= 0)
                    continue;
                
                $resolution_status = Deficiencies\ResolutionStatus::getAll();
                foreach($resolution_status as $resolution_id=>$resolution_label)
                {
                    $resolution_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'resolution_count');
                    $resolution_count->WhereEqual('resolution_status', $resolution_id, FieldType::INTEGER)
                        ->AndOp()
                        ->WhereEqual('status', $status_id, FieldType::INTEGER)
                        ->AndOp()
                        ->WhereEqual('type', $type_id, FieldType::INTEGER)
                    ;
                    
                    if(trim($_REQUEST['city']))
                    {
                        $resolution_count->AndOp()
                        ->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
                    }

                    $db->Count($resolution_count);

                    $resolution_total = $db->FetchArray()['resolution_count'];

                    print '<div class="data"><strong>'.$resolution_label.'</strong>: '.$resolution_total.'</div>';
                }
            }
        }
        
        /*$chart = new Highchart\Highchart();

        $chart->chart->renderTo = "container";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = "Browser market shares at a specific website, 2010";

        $chart->tooltip->formatter = new Highchart\HighchartJsExpr(
            "function() {
            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = 1;
        $chart->plotOptions->pie->dataLabels->color = "#000000";
        $chart->plotOptions->pie->dataLabels->connectorColor = "#000000";
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->series[] = array(
            'type' => "pie",
            'name' => "Browser share",
            'data' => array(
                array(
                    "Firefox",
                    45
                ),
                array(
                    "IE",
                    26.8
                ),
                array(
                    'name' => 'Chrome',
                    'y' => 12.8,
                    'sliced' => true,
                    'selected' => true
                ),
                array(
                    "Safari",
                    8.5
                ),
                array(
                    "Opera",
                    6.2
                ),
                array(
                    "Others",
                    0.7
                )
            )
        );

        $chart->printScripts();
        
        print '<div id="container"></div>';
        print '<script type="text/javascript">'.$chart->render("chart1").'</script>';*/
        
        print '</div>' . "\n";
        print '</div">' . "\n";
    ?>
    field;
    
row;