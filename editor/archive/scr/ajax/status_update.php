<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{

	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);
	$stat = intval($_GET['stat']);

	$query['status_save'] = "UPDATE {$path['db']}img SET"
						." status={$stat}, status_time={$tm}"
						." WHERE creator='{$usr}' AND time={$wh}";

	if (mysql_query($query['status_save'],$connection))	{ echo "<status usr=\"{$usr}\" wh=\"{$wh}\">{$stat}</status>"; }
	else 												{ echo "<status usr=\"{$usr}\" wh=\"{$wh}\">0</status>"; }
}

echo "</editor>";

?>