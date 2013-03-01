<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{

	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);
	$rt = intval($_GET['rt']);

	$query['rate_save'] = "UPDATE {$path['db']}img SET"
						." rating={$rt}, rating_time={$tm}"
						." WHERE creator='{$usr}' AND time={$wh}";

	if (mysql_query($query['rate_save'],$connection))	{ echo "<rate>1</rate>"; }
	else 												{ echo "<rate>0</rate>"; }
}

echo "</editor>";

?>