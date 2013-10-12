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

        $amount = 10;
        if(strlen(trim($_REQUEST['amount'])) > 0)
        {
            $_REQUEST['amount'] = intval($_REQUEST['amount']);

            if($_REQUEST['amount'] <= 30 && $_REQUEST['amount'] > 0)
                $amount = $_REQUEST['amount'];
        }

        $by_city = false;

        $select = new Cms\DBAL\Query\Select('deficiencies');

        $select_count = new Cms\DBAL\Query\Count('deficiencies', 'id', 'reports_count');

        if(trim($_REQUEST['city']))
        {
            $by_city = true;

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

        if(!$by_city)
        {
            if(trim($_REQUEST['lon']) && trim($_REQUEST['lat']))
            {
                $lat = doubleval($_REQUEST['lat']);
                $lon = doubleval($_REQUEST['lon']);

                if(Cms\System::GetRelationalDatabase()->type == Cms\Enumerations\DBDataSource::SQLITE)
                {
                    //More precise but slower
                    $select->SelectCustom("
                        distance(latitude, longitude, $lat, $lon) as distance, 
                        id, type, latitude, longitude, photo, comments, 
                        reports_count, status, report_timestamp, last_update,
                        line1, zipcode, city, country"
                    );
                    
                    $select->OrderBy('distance');
                }
                else
                {
                    //Less precise but faster
                    $select->SelectAll();
                    $select->OrderByCustom("abs((latitude+longitude+172)-($lat+$lon+172))");
                }
            }
            else
            {
                $select->SelectAll();
                $select->OrderBy('report_timestamp', Cms\Enumerations\Sort::DESCENDING);
            }
        }
        else
        {
            $select->SelectAll();
            $select->OrderBy('report_timestamp', Cms\Enumerations\Sort::DESCENDING);
        }
        
        //Just select items which status is unsolved
        if($select->HasWhere())
            $select->AndOp();

        $select->BeginExpr()
            ->WhereEqual(
                'resolution_status', 
                Deficiencies\ResolutionStatus::UNFIXED, 
                FieldType::INTEGER
            )
            ->OrOp()
            ->WhereEqual(
                'resolution_status', 
                Deficiencies\ResolutionStatus::IN_PROCESS, 
                FieldType::INTEGER
            )
            ->EndExpr()
        ;

        if($select_count->HasWhere())
            $select_count->AndOp();

        $select_count->BeginExpr()
            ->WhereEqual(
                'resolution_status', 
                Deficiencies\ResolutionStatus::UNFIXED, 
                FieldType::INTEGER
            )
            ->OrOp()
            ->WhereEqual(
                'resolution_status', 
                Deficiencies\ResolutionStatus::IN_PROCESS, 
                FieldType::INTEGER
            )
            ->EndExpr()
        ;

        //Limit the amount of results as display results by the requested page
        $limit_start = 0;

        if($page > 1)
            $limit_start = ($page-1) * $amount;

        $select->Limit($limit_start, $amount);
        
        $db = \Cms\System::GetRelationalDatabase();

        if($db->type == \Cms\Enumerations\DBDataSource::SQLITE)
            $db->pdo->sqliteCreateFunction(
                'distance', 
                '\Deficiencies\Distance::GetMiles', 
                4
            );

        //Count results
        $db->Count($select_count);

        $result = $db->Fetch();

        $count = $result['reports_count'];

        //Calculate pages
        $pages = 1;

        if($amount < $count)
        {
            $pages = floor($count / $amount);

            if(($count % $amount) > 0)
                $pages++;
        }

        //Return results
        $db->Select($select);

        $cities = array_flip(Deficiencies\Towns::GetAll());

        $reports = array();
        $reports_returned = 0;
        while($result = $db->FetchArray())
        {
            $result['city'] = $cities[$result['city']];
            $result['age'] = Cms\Utilities\Date::GetTimeElapsed($result["report_timestamp"]);
            $result['type_str'] = Deficiencies\Types::getType($result['type']);

            if(!$by_city)
            {
                if(trim($_REQUEST['lon']) && trim($_REQUEST['lat']))
                {
                    $distance = $result['distance'];

                    if($db->type == \Cms\Enumerations\DBDataSource::SQLITE)
                        $result['distance'] = round($result['distance'], 1);
                    else
                        $result['distance'] = round(
                            Deficiencies\Distance::GetMiles(
                                $result['latitude'], 
                                $result['longitude'], 
                                $lat, 
                                $lon
                            ), 
                            1
                        );

                    $time = $distance / 40; //distance / 40 m/h

                    if($distance > 40)
                    {
                        $hours = floor($time) . ' h';
                        $minutes = round(($time*60)%60);
                        $result['arrival_time'] = $hours . ($minutes > 0?' '.$minutes.' min':'');
                    }
                    else
                    {
                        $minutes = round(($time*60));
                        $result['arrival_time'] = $minutes . ' min';
                    }

                    if($result['distance'] >= 0.3)
                    {
                        $result['distance_unit'] = 'mi';
                    }
                    else
                    {
                        $result['distance'] = floor($distance * 5280);
                        $result['distance_unit'] = 'pies';
                    }
                }
            }

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