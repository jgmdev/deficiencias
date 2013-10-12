<?php exit; ?>

row: 0
    field: title
        <?=t('Report')?>
    field;
    
    field: content
    <?php
        Cms\Theme::AddScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=en');
        Cms\Theme::AddScript('scripts/jquery.gmap3.js');

        if(!($data = Deficiencies\Reports::GetData($_REQUEST['id'])))
            Cms\Uri::Go('');

        $cities = array_flip(Deficiencies\Towns::GetAll());
    ?>
        
        <script>
        $(document).ready(function(){
            $('#deficiency-view .map').gmap3({
                map:{
                    options:{
                        center:[<?=$data->latitude?>,<?=$data->longitude?>],
                        zoom:15,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
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
                    latLng:[<?=$data->latitude?>,<?=$data->longitude?>]
                }
            });
        });
        </script>
        
        <div id="deficiency-view">
            <table>
                <tr>
                    <td class="icon"></td>
                    <td class="deficiency">
                        <div class="type"><?=Deficiencies\Types::getType($data->type)?></div>
                        <div class="time-elapsed"><?=  Cms\Utilities\Date::GetTimeElapsed($data->report_timestamp)?></div>
                    </td>
                    <td class="confirm">
                        <a href="<?=Cms\Uri::GetUrl('reports/confirm', array('id'=>$data->id))?>"><?=t('Confirm')?> (<?=$data->reports_count?>)</a>
                    </td>
                </tr>
            </table>
            
            <div class="map-container">
                <div class="map" style="height: 275px"></div>
            </div>
            
            <div class="address-icon"></div>
            <p><span class="address-line"><?=$data->address->line1?></span><br /> 
            <span class="address-city"><?=$cities[$data->address->city]?>, Puerto Rico <?=$data->address->zipcode?></span></p>
           
            <div style="border-top: dotted 1px #d4d4d4; border-bottom: dotted 1px #d4d4d4; padding: 5px;">
                <div style="float:left; margin-right: 50px;">
                    <h3><?=t('Latitude')?></h3>
                    <p class="address-info"><?=$data->latitude?></p>
                </div>
                <div style="float:left; margin-left: 50px"> 
                    <h3><?=t('Longitude')?></h3>
                    <p class="address-info"><?=$data->longitude?></p>
                </div>
                <div style="clear:both"></div>
            </div>
            
            <h3><?=t('Comments')?></h3>
            <p class="report-comment"><?=$data->comments?></p>
        
        </div>
    field;
row;
