<?php exit; ?>

row: 0
    field: title
        Reporte
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
    
        
    field;
    
    field: description
        A page that displays a list of recent reports.
    field;
    
    field: keywords
        hoyos, derrumbes, tendido electrico
    field;
row;
