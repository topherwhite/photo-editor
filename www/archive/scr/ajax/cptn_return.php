<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");
require_once("../inc/xml_clean.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);
	$vers = mysqlclean($_GET,"vers",10,$connection);
	
	if ($vers == 1111111111) 	{ $qu = " AND versioned=0"; }
	else 						{ $qu = " AND updated={$vers} AND versioned=1"; }
	
//	die("SELECT * FROM {$path['db']}cptn WHERE usr='{$usr}' AND time={$wh}{$qu} LIMIT 1");
	
	if ($rtrn=mysql_fetch_array(mysql_query("SELECT * FROM {$path['db']}cptn WHERE usr='{$usr}' AND time={$wh}{$qu} LIMIT 1",$connection)))
	{

		
		echo "<cptn>".xml_clean_simple($rtrn['caption'])."</cptn>"
			."<time>{$wh}</time>"
			."<user>{$usr}</user>"
			;
	}
	
}

echo "</editor>";

?>