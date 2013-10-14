<?php exit; ?>

row: 0
    field: title
        SIRDE
    field;
    
    field: content
        <?php 
            Cms\Theme::AddStyle('styles/jquery.loadmask.css');
            
            Cms\Theme::AddScript('scripts/jquery.geolocation.js');
            Cms\Theme::AddScript('scripts/jquery.loadmask.js');
            Cms\Theme::AddScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=en');
            Cms\Theme::AddScript('scripts/jquery.gmap3.js');
            Cms\Theme::AddScript('script/reports/list');
            
            Cms\Theme::AddTab(t('Add Report'), 'reports/add');
        ?>
    
        <audio id="alert-sound" loop preload="auto">
            <source src="<?=Cms\Uri::GetUrl('audio/alert.mp3') ?>" type="audio/mpeg">
            <source src="<?=Cms\Uri::GetUrl('audio/alert.wav') ?>" type="audio/wav">
            Your browser does not support the audio element.
        </audio> 

        <table class="filter">
            <tr>
                <td class="town">
                    <label for="city"><?=t('City')?></label>
                    <select id="city">
                        <option value=""><?=t('All')?></option>
                    <?php
                        foreach(\Deficiencies\Towns::GetAll() as $label=>$value)
                        {
                            print "<option value=\"$value\">$label</option>";
                        }
                    ?>
                    </select>
                </td>
                
                <td class="type">
                    <label for="type"><?=t('Type')?></label>
                    <select id="type">
                        <option value=""><?=t('All')?></option>
                        <?php
                            foreach(\Deficiencies\Types::getAll() as $label=>$value)
                            {
                                print "<option value=\"$label\">$value</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    
        <div class="filter">
            <a class="monitor-button">Monitorear</a>
        </div>

        <div id="reports"></div>
        <br />
        <div style="text-align: center;" id="reports_footer">DEFICIENCIAS REPORTADAS: <span id="qty_reported">0</span></div>
    field;
    
    field: description
        A page that displays a list of recent reports.
    field;
    
    field: keywords
        hoyos, derrumbes, tendido electrico
    field;
row;
