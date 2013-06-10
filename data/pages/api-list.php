<?php exit; ?>

row: 0

    field: title
        Generate the list used when navigating the reports.
    field;
    
    field: content
        <?php
            use Cms\Enumerations\FieldType;
            
            $page = intval($_REQUEST['page']);
            
            if($page == 0)
                $page = 1;
            
            $amount = 5;
            if(strlen(trim($_REQUEST['amount'])) > 0)
            {
                $_REQUEST['amount'] = intval($_REQUEST['amount']);
                
                if($_REQUEST['amount'] <= 30 && $_REQUEST['amount'] > 0)
                    $amount = $_REQUEST['amount'];
            }
            
            $by_city = false;
            
            $select = new Cms\DBAL\Query\Select('deficiencies');
            $select->SelectAll();
            
            $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');
            
            if(trim($_REQUEST['city']))
            {
                $by_city = true;
                
                $select->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
                $select_count->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
            }
            
            if(strlen(trim($_REQUEST['type'])) > 0)
            {
                $select->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);
                $select_count->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);
            }
            
            if(!$by_city)
            {
                if(trim($_REQUEST['lon']) && trim($_REQUEST['lat']))
                {
                    $sum = doubleval($_REQUEST['lat']) + doubleval($_REQUEST['lon']);
                    
                    $select->OrderByCustom("abs((latitude+longitude)-($sum))");
                }
            }
            else
            {
                $select->OrderBy('report_timestamp', \Cms\Enumerations\Sort::DESCENDING);
            }
            
            $limit_start = 0;
            
            if($page > 1)
                $limit_start = $page * $amount;
            
            $select->Limit($limit_start, $amount);
            
            $db = \Cms\System::GetRelationalDatabase();
            
            //Count results
            $db->Count($select_count);
            
            $result = $db->Fetch();
            
            $count = $result['reports_count'];
            
            //Calculate pages
            $pages = 1;
            
            if($amount < $count)
            {
                $pages = floor($count / $amount);
            }
            
            //Return results
            //print $select->GetSQL(\Cms\DBAL\DataSource::SQLITE);
            
            $db->Select($select);
            
            $cities = array_flip(Deficiencies\Towns::GetAll());
            
            $reports = array();
            $reports_returned = 0;
            while($result = $db->FetchArray())
            {
                $result['city'] = $cities[$result['city']];
                $result['age'] = Cms\Utilities::GetTimeElapsed($result["report_timestamp"]);
                $result['type_str'] = Deficiencies\DeficiencyTypes::getType($result['type']);
                $reports[] = $result;
                
                $reports_returned++;
            }
            
            $output = array(
                'reports'=>$reports,
                'stats'=>array(
                    'current_page'=>$page,
                    'total_reports'=>$count,
                    'total_pages'=>$pages,
                    'amount_returned'=>$reports_returned,
                )
            );
            
            print Cms\Utilities\Json::Encode($output);
        ?>
    field;
    
    field: rendering_mode
        api
    field;
    
row;