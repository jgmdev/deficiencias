<?php exit; ?>

row: 0
    field: title
        Home Page
    field;
    
    field: content
        <?php 
            Cms\Theme::AddScript("test.js");
            Cms\Theme::AddStyle("test.css");
        ?>
    
        Welcome
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
