<!DOCTYPE html>
<html lang="<?=$language?>">

<head>
<title><?=$title?></title>
<?=$meta?>
<?php
$mobile_detect = new Cms\MobileDetect();
if($mobile_detect->isMobile()){
?>
<meta name="viewport" content="width=480px, user-scalable=yes, initial-scale=0">
<?php } ?>
<?=$styles?>
<?=$scripts?>
</head>


<body>

<div id="main-container">
    
<!--Header-->
<table id="header">
	<tr>
		<td class="logo">
            <a href="<?=$base_url?>">
                <img src="<?=$theme_url . "/images/logo.png"?>" />
            </a>
        </td>
		<td class="descripcion">
		<div style="textwidth: 250px; margin: 0 auto 0 auto;">
                <div style="font-size: 16px; color: #000">S I S T E M A</div>
                <div style="font-size: 18px; font-weight: bold; color: #34495e">Reporte de Deficiencias</div>
                <div style="color: #000">En las Carreteras de Puerto Rico</div>
                <div>( SIRDE )</div>
            </div>
        </td>
	</tr>
</table>

<table id="content">
	<tr>
		<td class="center">
            <?php if(Cms\Uri::GetCurrent() != 'home'){ ?>
			<h1><?=$content_title?></h1>
            <?php } ?>

			<?php if($messages){?>
			<div id="messages"><?=$messages?></div>
			<?php } ?>

			<?php if($tabs){?>
			<div id="tabs-menu"><?=$tabs?></div>
			<?php } ?>

			<?=$content?>
		</td>
	</tr>
</table>

<div id="footer">
	<?=$footer_message?>
</div>

</div>

</body>

</html>
