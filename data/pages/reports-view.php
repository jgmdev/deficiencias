<?php exit; ?>

row: 0
    field: title
        Reporte
    field;
    
    field: content
        <?php
            Cms\Theme::AddScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=en');
            Cms\Theme::AddScript('scripts/jquery.gmap3.js');
            
            if(!($data = \Deficiencies\Reports::GetData($_REQUEST['id'])))
                \Cms\Uri::Go('');
        ?>
    
        <script>
        $(document).ready(function(){
            $('#deficiency-view .map').gmap3({
                map:{
                    options:{
                        center:[<?=$data['latitude']?>,<?=$data['longitude']?>],
                        zoom:50,
                        mapTypeId: google.maps.MapTypeId.HYBRID,
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                        },
                        navigationControl: true,
                        scrollwheel: true,
                        streetViewControl: true
                    }
                },
                marker:{
                    latLng:[<?=$data['latitude']?>,<?=$data['longitude']?>]
                }
            });
        });
        </script>
        
        <div id="deficiency-view">
            <table>
                <tr>
                    <td class="icon"></td>
                    <td class="deficiency">
                        <div class="type"><?=Deficiencies\DeficiencyTypes::getType($data['type'])?></div>
                        <div class="time-elapsed"><?=  Cms\Utilities::GetTimeElapsed($data['report_timestamp'])?></div>
                    </td>
                    <td class="confirm">
                        <a href="<?=Cms\Uri::GetUrl('reports/confirm', array('id'=>$data['id']))?>">Confirmar (<?=$data['reports_count']?>)</a>
                    </td>
                </tr>
            </table>

            <div class="map" style="height: 300px"></div>
            
            <h3>Dirección</h3>
            <p><?=$data['line1']?>, <?=$data['city']?>, Puerto Rico, <?=$data['zipcode']?></p>
            
            <h3>Latitude</h3>
            <p><?=$data['latitude']?></p>
            
            <h3>Longitude</h3>
            <p><?=$data['longitude']?></p>
        
        </div>
    field;
    
    field: description
        A page that displays a list of recent reports.
    field;
    
    field: keywords
        hoyos, derrumbes, tendido electrico
    field;
row;
