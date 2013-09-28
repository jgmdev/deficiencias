<?php exit; ?>

row: 0

    field: title
        <?=t('Logged Out')?>
    field;
    
    field: content
    <?php
        Cms\Authentication::Logout();
        
        print t('You have successfully logged out from your account.');
    ?>
    field;
    
row;