<?php
exit;
?>

row: 0

    field: title
        <?=t('My Reports')?>
    field;
    
    field: content
    <?php
        if(!Cms\Authentication::IsUserLogged())
            Cms\Authentication::ProtectPage();
        
        $username = Cms\Authentication::GetUser()->username;
        
        if( Cms\Authentication::GetGroup()->HasPermission(
                Deficiencies\Permissions::ADMINISTRATOR
            )
        )
        {
            if(trim($_REQUEST['username']) != '')
            {
                $username = $_REQUEST['username'];
            }
        }
        
        $page = 1;
			
        if(isset($_REQUEST['page']))
        {
            $page = intval($_REQUEST['page']);
        }
        
        $select = new Cms\DBAL\Query\Select('deficiencies');
        $select->SelectAll()
            ->WhereEqual(
                'username', 
                $username, 
                Cms\Enumerations\FieldType::TEXT
            )
        ;
        
        $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');
        $select_count->WhereEqual(
                'username', 
                $username, 
                Cms\Enumerations\FieldType::TEXT
        );
        
        $db = Cms\System::GetRelationalDatabase();
        
        $reports_total = 0;
        
        if($db->Count($select_count))
            $reports_total = $db->FetchArray()['reports_count'];
        
        print '<div id="reports">';

        print '<h2>' . t('Total reports:') . ' ' . $reports_total . '</h2>';

        $select->OrderBy('report_timestamp', Cms\Enumerations\Sort::DESCENDING)
            ->Limit($page-1, 30)
        ;
        
        $db->Select($select);
        
        $main_url = Cms\Uri::GetUrl('');
        $cities = array_flip(Deficiencies\Towns::GetAll());

        print '<table class="list">' . "\n";

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

            print '<td>';
            print '<a class="location" style="'.$style.'" href="'.
                Cms\Uri::GetUrl('reports/view', array('id'=>$report['id'])).
                '"></a>';
            print '</td>';

            print '<td class="details">';
            print '<a href="'.
                Cms\Uri::GetUrl('reports/view', array('id'=>$report['id'])).
                '">';
            print '<div class="route">'.$report['line1'].'</div>';
            print '<span class="city">';
            print $cities[$report['city']] . ', ' . 'PR';
            print '</span>';
            print '<div class="type">'.Deficiencies\Types::getType($report['type']).'</div>';
            print '</a>';
            print '</td>';
            
            print '<td class="status">';
            print Deficiencies\Status::getStatus($report['status']);
            print '</td>';

            print '</tr>';
        }
        print '</tbody>' . "\n";

        print '</table>';
        
        print '</div>';

        print Cms\Utilities::GenerateNavigation(
            $reports_total, $page, 'account/reports', "", 30, 
            array('username'=>$username)
        );
        
    ?>
    field;
    
row;
