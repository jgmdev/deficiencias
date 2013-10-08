<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0
    field: title
        <?=t("My Account")?>
    field;

    field: content
    <?php
        if(Cms\Authentication::IsUserLogged())
            print Cms\Users::GenerateUserPage();
        else
            Cms\Uri::Go('login');
    ?>
    field;
row;
