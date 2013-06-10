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
        $geocodes_full = $MAP_OBJECT->reverseGeoGetAddress(-65.8232705, 18.2205713);
    ?>

        <h2>Cached response:</h2>
        <pre><?=print_r($geocodes,true)?></pre>

        <h2>Full response:</h2>
        <pre><?=print print_r($geocodes_full)?></pre>
        
        <?php
            /*Cms\Theme::AddScript('scripts/jquery-1.8.2.min.js');
            Cms\Theme::AddScript('scripts/jquery.geolocation.js');
            Cms\Theme::AddScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=en');
            Cms\Theme::AddScript('scripts/jquery.gmap3.js');*/
        ?>
        
        <script>
        /*$(document).ready(function(){
            $.geolocation.get({
                win: function(position){
                    $('body').unmask();
                    $('#coords').show();
                    $('#lon').val(position.coords.longitude);
                    $('#lat').val(position.coords.latitude);
                },
                fail: function(position){
                    alert("No se pudo obtener su ubicación.\nEntre la dirección física del área lo mas certero posible.");
                }
            });
        
            $('body').gmap3({
                getaddress:{
                    latLng:[18.2205727, -65.8232736],
                    callback:function(results){
                        for(field in results[0].address_components){
                            for(type in results[0].address_components[field].types)
                                if(results[0].address_components[field].types[type] == 'administrative_area_level_1')
                                    alert(results[0].address_components[field].long_name);
                        }
                    }
                }
            });
        });*/
        </script>
    field;
    
row;