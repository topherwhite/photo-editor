<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{

	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);
	$lf = round(intval($_GET['lf'])/$_GET['cn']);
	$tp = round(intval($_GET['tp'])/$_GET['cn']);
	$wd = round(intval($_GET['wd'])/$_GET['cn']);
	$ht =round(intval($_GET['ht'])/$_GET['cn']);

	$query['crop_save'] = "UPDATE {$path['db']}img SET"
						." crop_wd={$wd}, crop_ht={$ht}"
						.", crop_x={$lf}, crop_y={$tp}"
						." WHERE creator='{$usr}' AND time={$wh}";

	if (mysql_query($query['crop_save'],$connection))	{ echo "<crop>1</crop>"; }
	else 												{ echo "<crop>0</crop>"; }
}


echo "</editor>";

?>