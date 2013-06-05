<?php
/**
 *Copyright 2008, Jefferson González (JegoYalu.com)
 *This file is part of Jaris CMS and licensed under the GPLPP,
 *check the LICENSE.txt file for version and details or visit
 *http://gplpp.org/license.
*/
?>
<!DOCTYPE html>
<html lang="<?=$language?>">

<head>
<title><?=$title?></title>
<?=$header_info?>
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
