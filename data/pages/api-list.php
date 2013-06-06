<?php exit; ?>

row: 0

    field: title
        Generate the list used when navigating the reports.
    field;
    
    field: content
        <?php
            use Cms\Enumerations\FieldType;
            
            $page = intval($_REQUEST['page']);
            
            if(strlen(trim($_REQUEST['type'])) > 0)
            {
                $where .= "type=".intval($_REQUEST['type'])." and ";
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
            
            $db->CustomQuery("select count(id) as reports_count from deficiencies $where");
            
            $result = $db->Fetch();
            
            $count = $result['reports_count'];
            
            
        ?>
    field;
    
    field: rendering_mode
        api
    field;
    
row;