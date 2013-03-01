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
	$tag = mysqlclean($_GET,"tag",10,$connection);
	
	
	if (	mysql_query("UPDATE {$path['db']}cptn SET updated={$tm}, versioned=1, version_tag='{$tag}' WHERE usr='{$usr}' AND time={$wh} AND versioned=0",$connection)
		&&	($rtrn=mysql_fetch_array(mysql_query("SELECT * FROM {$path['db']}cptn WHERE usr='{$usr}' AND time={$wh} ORDER BY updated DESC LIMIT 1",$connection)))
		&&	(mysql_query("INSERT INTO {$path['db']}cptn SET usr='{$usr}', time={$wh}, updated={$tm}, caption='".mysqlclean($rtrn,'caption',1500,$connection)."'",$connection))
		)
	{

		
		echo "<cptn>".xml_clean_simple($rtrn['caption'])."</cptn>"
			."<last_time>".$tm."</last_time>"
			."<last_tag>".date("D, j M, G:i",$tm)." - ".xml_clean_simple($rtrn['version_tag'])."</last_tag>"
			."<time>{$wh}</time>"
			."<user>{$usr}</user>"
			;
	}
	
}

echo "</editor>";

?>