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

        $page = intval($_REQUEST['page']);

        if($page == 0)
            $page = 1;

        $select = new Cms\DBAL\Query\Select('deficiencies');

        $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');

        if(trim($_REQUEST['city']))
        {
            $select->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
            $select_count->WhereEqual('city', $_REQUEST['city'], FieldType::TEXT);
        }

        if(strlen(trim($_REQUEST['type'])) > 0)
        {
            if($select->HasWhere())
                $select->AndOp();

            $select->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);

            if($select_count->HasWhere())
                $select_count->AndOp();

            $select_count->WhereEqual('type', $_REQUEST['type'], FieldType::INTEGER);
        }

        $limit_start = 0;

        if($page > 1)
            $limit_start = ($page-1) * 100;

        $select->Limit($limit_start, 100);

        $db = \Cms\System::GetRelationalDatabase();

        //Count results
        $db->Count($select_count);

        $result = $db->Fetch();

        $count = $result['reports_count'];

        //Return results
        $db->Select($select);

        print Cms\Utilities::GenerateNavigation(
            $count, 
            $page, 
            'admin/deficiencies', 
            '', 
            100, 
            array()
        );

        while($result = $db->FetchArray())
        {
            
        }
        
        print Cms\Utilities::GenerateNavigation(
            $count, 
            $page, 
            'admin/deficiencies', 
            '', 
            100, 
            array()
        );
    ?>
    field;
    
    field: rendering_mode
        api
    field;
    
row;