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
            Cms\Theme::AddScript('script/reports/list');
        ?>
    
        <table class="filter">
            <tr>
                <td class="town">
                    <div><strong>Ubicación</strong></div>
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
                    <div><strong>Tipo de Deficiencia</strong></div>
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
    
        <table id="list"></table>
        <table id="listnavigation"></table>
    
        <table class="filter" style="margin-top: 20px;">
            <tr>
                <td>
                    <h1 style="text-transform: uppercase; font-size: 14px; font-weight: bold;">
                        Ultimas deficiencias reportadas
                    </h1>
                </td>
                
                <td>
                    <a class="add-button" href="<?=Cms\Uri::GetUrl('reports/add')?>">Reportar Nueva</a>
                </td>
            </tr>
        </table>
        
        <div id="reports"></div>
    field;
    
    field: description
        A page that displays a list of recent reports.
    field;
    
    field: keywords
        hoyos, derrumbes, tendido electrico
    field;
    
    field: rendering_mode
        html
    field;
row;
