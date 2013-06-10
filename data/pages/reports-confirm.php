<?php exit; ?>

row: 0
    field: title
        Confirmar Reporte
    field;
    
    field: content
        <?php
            \Deficiencies\Reports::AddConfirm($_REQUEST['id']);
            
            \Cms\Theme::AddMessage(t('Confirmation added.'));
            
            \Cms\Uri::Go('reports/view', array('id'=>$_REQUEST['id']));
        ?>
    field;
    
row;
