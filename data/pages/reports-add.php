<?php exit; ?>

row: 0
    field: title
        Reportar Problema
    field;
    
    field: content
        <?php 
            Cms\Theme::AddStyle("styles/jquery.loadmask.css");
            
            Cms\Theme::AddScript('scripts/jquery-1.8.2.min.js');
            Cms\Theme::AddScript('scripts/jquery.geolocation.js');
            Cms\Theme::AddScript("scripts/jquery.loadmask.js");
            Cms\Theme::AddScript('script/reports/add');
        ?>
    
        <?php
            if(isset($_REQUEST["btnSave"]))
            {
                $deficiency = new Deficiencies\Deficiency;
                $deficiency->type = $_REQUEST["type"];
                $deficiency->latitude = $_REQUEST["lat"];
                $deficiency->longitude = $_REQUEST["lon"];
                $deficiency->comments = $_REQUEST["comments"];
                
                $deficiency->address->line1 = $_REQUEST["address"];
                $deficiency->address->zipcode = $_REQUEST["zip"];
                $deficiency->address->city = $_REQUEST["city"];
                $deficiency->address->country = 'Puerto Rico';
                
                \Deficiencies\Reports::Add($deficiency);
            }
        ?>
    
        <form method="post" action="<?=\Cms\Uri::GetUrl('reports/add')?>">
            <label for="type">Tipo</label>
            <select id="type" name="type">
                <option value="1">Hoyo en la carretera</option>
            </select>
            
            <div id="coords" style="display: none;">
            <label for="lat">Latitud</label>
            <input type="text" readonly="readonly" id="lat" name="lat" />
            
            <label for="lon">Longitud</label>
            <input type="text" readonly="readonly" id="lon" name="lon" />
            </div>
            
            <div id="address" style="display: none">
            <label for="address">Dirección Física</label>
            <textarea id="address" name="address"></textarea>
            
            <label for="zip">Zipcode</label>
            <input type="text" id="zip" name="zip" />
            </div>
            
            <label for="city">Pueblo</label>
            <select id="city" name="city">
            <?php
                foreach(\Deficiencies\Towns::GetAll() as $label=>$value)
                {
                    print "<option value=\"$value\">$label</option>";
                }
            ?>
            </select>
            
            <label for="comments">Comentarios</label>
            <textarea id="comments" name="comments"></textarea>
            
            <input type="submit" id="report" name="btnSave" value="Enviar" />
        </form>
        
    field;
    
    field: rendering_mode
        html
    field;
row;
