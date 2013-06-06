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
            if(is_array($component['types']))
            {
                foreach($component['types'] as $type)
                {
                    if($type == 'administrative_area_level_1')
                    {
                        $town = $uri = str_ireplace(
                            array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ",
                            "Á", "É", "Í", "Ó", "Ú", "Ä", "Ë", "Ï", "Ö", "Ü", "Ñ"), 
                            array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n",
                            "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
                            $component['long_name']
                        );

                        $town = strtolower(str_replace(' ', '_', $town));
                        print $town;
                        break 2;
                    }
                }
            }
        }
    ?>
    field;
    
    field: rendering_mode
        api
    field;
row;