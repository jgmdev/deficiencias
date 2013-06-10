<!DOCTYPE html>
<html lang="<?=$language?>">

<head>
<title><?=$title?></title>
<?=$meta?>
<?php
$mobile_detect = new Cms\MobileDetect();
if($mobile_detect->isMobile()){
?>
<meta name="viewport" content="width=490px, user-scalable=yes, initial-scale=0">
<?php } ?>
<?=$styles?>
<?=$scripts?>

<?php if($messages){ ?>
<script>
$(document).ready(function(){
   $('#messages').fadeOut(10000);
});
</script>
<?php } ?>
</head>


<body>

<div id="header-menu" style="<?=$mobile_detect->isMobile()?'position: fixed; top: 0':''?>">
    <a title="home" class="home" href="<?=$base_url?>">
        <img src="<?=$theme_url?>/images/home.png" />
    </a>
    
    <h1><?=$content_title?></h1>
    
    <a title="add report" class="add-report" href="<?=$base_url?>/reports/add">
        <img src="<?=$theme_url?>/images/add-deficiency.png" />
    </a>
    <div style="clear: both"></div>
</div>
    
<div id="main-container" style="<?=$mobile_detect->isMobile()?'':'margin-top: 0;'?>">

<?php if($messages){?>
<div id="messages"><?=$messages?></div>
<?php } ?>

<table id="content">
	<tr>
		<td class="center">
			<?php if($tabs){?>
			<div id="tabs-menu"><?=$tabs?></div>
			<?php } ?>

			<?=$content?>
		</td>
	</tr>
</table>
    
<!--Header-->
<table id="header">
	<tr>
		<td class="logo">
            <a href="<?=$base_url?>">
                <img src="<?=$theme_url . "/images/logo.png"?>" />
            </a>
        </td>
		<!--<td class="descripcion">
		<div style="margin: 0 auto 0 auto;">
                <div style="font-size: 16px; color: #000">S I S T E M A</div>
                <div style="font-size: 18px; font-weight: bold; color: #34495e">Reporte de Deficiencias</div>
                <div style="color: #000">En las Carreteras de Puerto Rico</div>
                <div>( SIRDE )</div>
            </div>
        </td>-->
	</tr>
</table>

<div id="footer">
	<?=$footer_message?>
</div>

</div>

</body>

</html>
