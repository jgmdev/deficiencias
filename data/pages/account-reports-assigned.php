<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Assigned Reports')?>
    field;
    
    field: content
    <?php
        if(!Cms\Authentication::GetGroup()->HasPermission(Deficiencies\Permissions::ADMINISTRATOR))
            Cms\Authentication::ProtectPage(Deficiencies\Permissions::ATTENDANT);
        
        $username = Cms\Authentication::GetUser()->username;
        
        if(Cms\Authentication::GetGroup()->HasPermission(Deficiencies\Permissions::ADMINISTRATOR))
        {
            if(isset($_REQUEST['username']))
                $username = trim($_REQUEST['username']);
        }
                
        use Cms\Enumerations\FieldType;
        
        print '<div class="cmsgui">' . "\n";
        print '<div class="accounts-reports-assigned">' . "\n";
        
        if(Cms\Authentication::GetGroup()->HasPermission(Deficiencies\Permissions::ADMINISTRATOR))
        {
            if(isset($_REQUEST['username']))
                print '<strong>'.t('Reports assigned to:').'</strong> ' .
                Cms\Users::GetData($_REQUEST['username'])->fullname;
        }
        
        $form = new Cms\Form('admin-deficiencies', null, Cms\Enumerations\FormMethod::GET);
        
        $form->AddField(new Cms\Form\Field\Hidden(
            'username', $_REQUEST['username']
        ));
        
        $filter_group = new Cms\Form\FieldsGroup(t('Filters'), '', true);
        
        $cities = Deficiencies\Towns::GetAll();
        $cities = array(t('All')=>'') + $cities;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('City'), 'city', $cities
        ));
        
        $types = array_flip(Deficiencies\Types::getAll());
        $types = array(t('All')=>'') + $types;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('Type'), 'type', $types
        ));
        
        $status = array_flip(Deficiencies\Status::getAll());
        $status = array(t('All')=>'') + $status;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('Status'), 'status', $status
        ));
        
        $resolution_status = array_flip(Deficiencies\ResolutionStatus::getAll());
        $resolution_status = array(t('All')=>'') + $resolution_status;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('Resolution Status'), 'resolution_status', $resolution_status
        ));
        
        $priority = array_flip(Deficiencies\Priority::getAll());
        $priority = array(t('All')=>'') + $priority;
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('Priority'), 'priority', $priority
        ));
        
        $order_by = array(
            t('None')=>'',
            t('Reports count ascending')=>'rcount_asc',
            t('Reports count descending')=>'rcount_desc',
            t('Date ascending')=>'date_asc',
            t('Date descending')=>'date_desc',
            t('Re-open count ascending')=>'rocount_asc',
            t('Re-open count descending')=>'rocount_desc'
        );
        
        $filter_group->AddField(new Cms\Form\Field\Select(
            t('Order by'), 'order_by', $order_by
        ));
        
        $filter_group->AddField(new Cms\Form\Field\Submit('Filter', 'btnFilter'));
        
        $form->AddGroup($filter_group);
        
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
        
        if(trim($_REQUEST['resolution_status']) != '')
        {
            if($select->HasWhere())
                $select->AndOp();

            $select->WhereEqual('resolution_status', $_REQUEST['resolution_status'], FieldType::INTEGER);

            if($select_count->HasWhere())
                $select_count->AndOp();

            $select_count->WhereEqual('resolution_status', $_REQUEST['resolution_status'], FieldType::INTEGER);
        }
        
        if(trim($_REQUEST['priority']) != '')
        {
            if($select->HasWhere())
                $select->AndOp();

            $select->WhereEqual('priority', $_REQUEST['priority'], FieldType::INTEGER);

            if($select_count->HasWhere())
                $select_count->AndOp();

            $select_count->WhereEqual('priority', $_REQUEST['priority'], FieldType::INTEGER);
        }
        
        if($select->HasWhere())
                $select->AndOp();

        $select->WhereEqual('assigned_to', $username, FieldType::TEXT);

        if($select_count->HasWhere())
            $select_count->AndOp();

        $select_count->WhereEqual('assigned_to', $username, FieldType::TEXT);
        
        if(trim($_REQUEST['order_by']) != '')
        {
            if($_REQUEST['order_by'] == 'rcount_asc')
                $select->OrderBy('reports_count');
            
            elseif($_REQUEST['order_by'] == 'rcount_desc')
                $select->OrderBy('reports_count', Cms\Enumerations\Sort::DESCENDING);
            
            elseif($_REQUEST['order_by'] == 'date_asc')
                $select->OrderBy('report_timestamp');
            
            elseif($_REQUEST['order_by'] == 'date_desc')
                $select->OrderBy('report_timestamp', Cms\Enumerations\Sort::DESCENDING);
            
            elseif($_REQUEST['order_by'] == 'rocount_asc')
                $select->OrderBy('reopened_count');
            
            elseif($_REQUEST['order_by'] == 'rocount_desc')
                $select->OrderBy('reopened_count', Cms\Enumerations\Sort::DESCENDING);
        }

        $limit_start = 0;

        if($page > 1)
            $limit_start = ($page-1) * 10;

        $select->Limit($limit_start, 10);

        $db = Cms\System::GetRelationalDatabase();

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
        print '<td class="details">' . t('Address') . '</td>' . "\n";
        print '<td class="status">' . t('Status') . '</td>' . "\n";
        print '<td class="resolution">' . t('Resolution') . '</td>' . "\n";
        print '<td class="priority">' . t('Priority') . '</td>' . "\n";
        print '<td class="status">' . t('Date') . '</td>' . "\n";

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

            print '<td class="icon">';
            print '<a class="location" style="'.$style.'" href="'.
                Cms\Uri::GetUrl('admin/deficiencies/edit', array('id'=>$report['id'])).
                '"></a>';
            print '</td>';

            print '<td class="details">';
            print '<a href="'.
                Cms\Uri::GetUrl('admin/deficiencies/edit', array('id'=>$report['id'])).
                '">';
            print '<div class="route">'.$report['line1'].'</div>';
            print '<span class="city">';
            print $cities[$report['city']] . ', ' . 'PR';
            print '</span>';
            print '<div class="type">'.Deficiencies\Types::getType($report['type']).'</div>';
            print '</a>';
            print '</td>';
            
            print '<td class="status">';
            //print Deficiencies\Status::getStatus($report['status']);
            print Deficiencies\Status::getStatus($report['status']);
            print '</td>';
            
            print '<td class="resolution">';
            //print Deficiencies\Status::getStatus($report['status']);
            print Deficiencies\ResolutionStatus::getStatus($report['resolution_status']);
            print '</td>';
            
            print '<td class="priority">';
            //print Deficiencies\Status::getStatus($report['status']);
            print Deficiencies\Priority::getStatus($report['priority']);
            print '</td>';
            
            print '<td class="date">';
            //print Deficiencies\Status::getStatus($report['status']);
            print Cms\Utilities\Date::GetTimeElapsed($report['report_timestamp']);
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
                'status'=>$_REQUEST['status'],
                'resolution_status'=>$_REQUEST['resolution_status'],
                'priority'=>$_REQUEST['priority'],
                'order_by'=>$_REQUEST['order_by'],
                'username'=>$_REQUEST['username']
            )
        );
        
        print '</div>' . "\n";
        print '</div">' . "\n";
    ?>
    field;
    
row;