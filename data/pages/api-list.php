<?php exit; ?>

row: 0

    field: title
        Generate the list used when navigating the reports.
    field;
    
    field: content
        <?php
            use Cms\Enumerations\FieldType;
            
            $page = intval($_REQUEST['page']);
            
            $amount = 5;
            if(strlen(trim($_REQUEST['amount'])) > 0)
            {
                $_REQUEST['amount'] = intval($_REQUEST['amount']);
                
                if($_REQUEST['amount'] <= 30 && $_REQUEST['amount'] > 0)
                    $amount = $_REQUEST['amount'];
            }
            
            $where = '';
            
            if(trim($_REQUEST['city']))
            {
                $where .= "city='".str_replace("'", "''", $_REQUEST['city'])."' and ";
            }
            
            if(strlen(trim($_REQUEST['type'])) > 0)
            {
                $where .= "type=".intval($_REQUEST['type'])." and ";
            }
            
            if($where == '')
            {
                if(trim($_REQUEST['lon']))
                {
                    $where .= "longitude=".doubleval($_REQUEST['lon'])." and ";
                }
                
                if(trim($_REQUEST['lat']))
                {
                    $where .= "latitude=".doubleval($_REQUEST['lat'])." and ";
                }
            }
            
            if($where)
            {
                $where = 'where ' . rtrim($where, ' and');
            }
            
            $db = \Cms\System::GetRelationalDatabase();
            
            $limit_start = 0;
            
            if(($page + 1) > 1)
                $limit_start = ($page+1) * $amount;
            
            $db->CustomQuery("select count(id) as reports_count from deficiencies $where limit $limit_start,$amount");
            
            $result = $db->Fetch();
            
            $count = $result['reports_count'];
            
            $pages = 1;
            
            if($amount < $pages)
            {
                $pages = ceil($count / $amount);
            }
            
            
            $db->CustomQuery("select * from deficiencies $where");
            
            $reports = array();
            $reports_returned = 0;
            while($result = $db->FetchArray())
            {
                $result['age'] = Cms\Utilities::GetTimeElapsed($result["report_timestamp"]);
                $reports[] = $result;
                $reports_returned++;
            }
            
            $output = array(
                'reports'=>$reports,
                'stats'=>array(
                    'current_page'=>$page+1,
                    'total_reports'=>$count,
                    'total_pages'=>$pages,
                    'amount_returned'=>$reports_returned
                )
            );
            
            print Cms\Utilities\Json::Encode($output);
        ?>
    field;
    
    field: rendering_mode
        api
    field;
    
row;