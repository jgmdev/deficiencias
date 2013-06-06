<?php exit; ?>

row: 0
    field: title
        <?=t('Problem Report')?>
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
                $valid = true;
                $has_photo = false;
                
                if (!empty($_FILES['photo']['name'])) {
                    $photo = $_FILES['photo'];

                    if (strpos($photo['type'], 'image') === FALSE || $photo['size'] > 2097152222222) {
                        $valid = false;
                    } else {
                        $has_photo = true;
                    }
                }
                
                if ($valid) {
                    $deficiency = new Deficiencies\Deficiency;
                    $deficiency->type = $_REQUEST["type"];
                    $deficiency->latitude = $_REQUEST["lat"];
                    $deficiency->longitude = $_REQUEST["lon"];
                    $deficiency->comments = $_REQUEST["comments"];
                    
                    $deficiency->address->line1 = $_REQUEST["address"];
                    $deficiency->address->zipcode = $_REQUEST["zip"];
                    $deficiency->address->city = $_REQUEST["city"];
                    $deficiency->address->country = 'Puerto Rico';

                    if ($has_photo) {
                        $photo_dir = './def_images/def_photo' . time() . strrchr($photo['name'], '.');
                        move_uploaded_file($photo['tmp_name'], $photo_dir);
                        
                        $deficiency->photo = $photo_dir;
                    } else {
                        $deficiency->photo = null;
                    }
                    
                    $id = \Deficiencies\Reports::Add($deficiency);
                    
                    echo sprintf(t('The report has been submitted. Thanks for your collaboration. (ID: %s)'), $id);
                } else {
                    echo t('A valid file is an image with max file size of 2MB.');
                }
                
            } else {
        ?>
    
        <form method="post" action="<?=\Cms\Uri::GetUrl('reports/add')?>" enctype="multipart/form-data">
            <label for="type"><?=t('Type')?></label>
            <select id="type" name="type">
            <?php
                foreach(\Deficiencies\DeficiencyTypes::getAll() as $label=>$value)
                {
                    print "<option value=\"$label\">$value</option>";
                }
            ?>
            </select>
            
            <div id="coords" style="display: none;">
            <label for="lat"><?=t('Latitude')?></label>
            <input type="text" readonly="readonly" id="lat" name="lat" />
            
            <label for="lon"><?=t('Longitude')?></label>
            <input type="text" readonly="readonly" id="lon" name="lon" />
            </div>
            
            <div id="address" style="display: none">
            <label for="address"><?=t('Physical Address')?></label>
            <textarea id="address" name="address"></textarea>
            
            <label for="zip"><?=t('Zipcode')?></label>
            <input type="text" id="zip" name="zip" />
            </div>
            
            <label for="city"><?=t('City')?></label>
            <select id="city" name="city">
            <?php
                foreach(\Deficiencies\Towns::GetAll() as $label=>$value)
                {
                    print "<option value=\"$value\">$label</option>";
                }
            ?>
            </select>
            
            <label for="photo"><?=t('Photo')?></label>
            <input type="file" id="photo" name="photo" />
            
            <label for="comments"><?=t('Comments')?></label>
            <textarea id="comments" name="comments"></textarea>
            
            <input type="submit" id="report" name="btnSave" value="<?=t('Send')?>" />
        </form>
        
        <? } ?>
        
    field;
    
    field: rendering_mode
        html
    field;
row;
