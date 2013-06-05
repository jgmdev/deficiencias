<!DOCTYPE html>
<html lang="<?=$language?>">

<head>
<title><?=$title?></title>
<?=$meta?>
<?=$styles?>
<?=$scripts?>
</head>


<body>

<!--Header-->
<table id="header">
	<tr>
		<td class="logo"></td>
		<td class="menu"></td>
	</tr>
</table>

<table id="content">
	<tr>
		<td class="center">
			<h1><?=$content_title?></h1>

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


</body>

</html>
