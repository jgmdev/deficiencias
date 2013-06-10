<?php exit; ?>

row: 0
    field: title
        Home Page
    field;
    
    field: content
        <?php 
            Cms\Theme::AddStyle("styles/jquery.loadmask.css");
            
            Cms\Theme::AddScript('scripts/jquery-1.8.2.min.js');
            Cms\Theme::AddScript('scripts/jquery.geolocation.js');
            Cms\Theme::AddScript("scripts/jquery.loadmask.js");
            Cms\Theme::AddScript('http://maps.google.com/maps/api/js?sensor=false&amp;language=en');
            Cms\Theme::AddScript('scripts/jquery.gmap3.js');
            Cms\Theme::AddScript('script/reports/list');
        ?>
    
        <table class="filter">
            <tr>
                <td class="town">
                    <label for="city"><?=t('City')?></label>
                    <select id="city">
                        <option value="">Todos</option>
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
                            foreach(\Deficiencies\DeficiencyTypes::getAll() as $label=>$value)
                            {
                                print "<option value=\"$label\">$value</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    
        <table class="filter" style="margin-top: 20px;">
            <tr>
                <td class='left_button'>
                    <h1 style="text-transform: uppercase; font-size: 14px; font-weight: bold;">
                        Últimas deficiencias reportadas
                    </h1>
                </td>
                
                <td class='right_button'>
                    <a class="add-button" href="<?=Cms\Uri::GetUrl('reports/add')?>">Reportar Nueva</a>
                </td>
            </tr>
        </table>
        
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
