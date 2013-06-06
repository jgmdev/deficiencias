<?php exit; ?>

row: 0

    field: title
        Gets the town from a given set of coords
    field;
    
    field: content
    <?php
        $MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = false;
        
        //setDSN is optional
        $datasource = new Cms\DBAL\DataSource;
        $datasource->InitAsSQLite('geocodes', Cms\System::GetDataPath() . "sqlite");
        $MAP_OBJECT->setDSN($datasource);
        
        $address = $MAP_OBJECT->reverseGeoGetAddress(doubleval($_REQUEST["lon"]), doubleval($_REQUEST["lat"]));
        
        $data = Cms\Utilities\Json::Decode(Cms\Utilities\Json::Encode($address));
        
        foreach($data['results'][0]['address_components'] as $component)
        {
            foreach($component['types'] as $type)
            {
                if($type == 'administrative_area_level_1')
                {
                    print \Cms\Uri::TextToUri(str_replace(' ', '_', $component['long_name']));
                    break 2;
                }
            }
        }
    ?>
    field;
    
    field: rendering_mode
        api
    field;
row;