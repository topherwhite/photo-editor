<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{

	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);
	$do = intval($_GET['do']);
	

	$query['delete'] = "UPDATE {$path['db']}img SET"
						." deleted={$do}"
						." WHERE creator='{$usr}' AND time={$wh}";

	if (mysql_query($query['delete'],$connection))		{ echo "<delete>1</delete>"; }
	else 												{ echo "<delete>0</delete>"; }
}

echo "</editor>";

?>