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
            Cms\Theme::AddScript('scripts/listing.js');
        ?>
    
        <table id="filter">
            <tr>
                <td class="town">
                    <div>Ubicación</div>
                    <select id="town">
                        <option value="">Todos</option>
                        <!--<option style="display: none" value="near" class="near">En Mi Area</option>-->
                    <?php
                        foreach(\Deficiencies\Towns::GetAll() as $label=>$value)
                        {
                            print "<option value=\"$value\">$label</option>";
                        }
                    ?>
                    </select>
                </td>
                
                <td class="type">
                    <div>Ubicación</div>
                    <select id="type">
                        <option value="">Todos</option>
                        <option style="display: none" value="near">En Mi Area</option>
                    <?php
                        foreach(\Deficiencies\Towns::GetAll() as $label=>$value)
                        {
                            print "<option value=\"$value\">$label</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            
            
        </table>
    
        
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
