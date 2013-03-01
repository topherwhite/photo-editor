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

	$orig_is_hires[1] = "1111111111";	
	$orig_is_hires[2] = "1234567890";
	
	if ( ($do == 1) && file_exists("{$path['bambi']}/final/".date("Y_m_d",$wh)."/{$usr}.{$wh}.jpg") ) { $orig_is_hires[1] = $tm; }

	$query['orig_is_hires'] = "UPDATE {$path['db']}img SET hires_added={$orig_is_hires[$do]} WHERE creator='{$usr}' AND time={$wh}";
	
	if (mysql_query($query['orig_is_hires'],$connection)) 	{ echo "<done>1</done>"; }
	else 													{ echo "<done>0</done>"; }
	
	echo "<value>{$orig_is_hires[$do]}</value>"
		."<usr>{$usr}</usr>"
		."<time>{$wh}</time>";
}

echo "</editor>";

?>