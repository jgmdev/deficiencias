<?php exit; ?>

row: 0

    field: title
        Location testing with googlemaps
    field;
    
    field: content
    <?php
        $MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = false;
        
        //setDSN is optional
        $datasource = new Cms\DBAL\DataSource;
        $datasource->InitAsSQLite('geocodes', Cms\System::GetDataPath() . "sqlite");
        $MAP_OBJECT->setDSN($datasource);
        
        $geocodes = $MAP_OBJECT->getGeoCode("Vail, CO");
        $geocodes_full = $MAP_OBJECT->geoGetCoordsFull("Vail, CO");
        
        
    ?>

        <h2>Cached response:</h2>
        <pre><?=print_r($geocodes,true)?></pre>

        <h2>Full response:</h2>
        <pre><?=print print_r($geocodes_full)?></pre>
    field;
    
row;