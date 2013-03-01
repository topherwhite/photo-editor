<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");
require_once("../inc/xml_clean.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$tp = mysqlclean($_GET,"tp",15,$connection);
	$vl = mysqlclean($_GET,"vl",100,$connection);
	$nm = mysqlclean($_GET,"nm",100,$connection);
	$action = intval($_GET['do']);

	if ($action == 1)
	{	$query['val_do'] = "INSERT INTO {$path['db']}value_lists SET"
						." value_type='{$tp}', value='{$vl}'"
						.", value_name='{$nm}', created={$tm}";
	}
	elseif ($action == 2)
	{	$query['val_do'] = "DELETE FROM {$path['db']}value_lists WHERE"
						." value_type='{$tp}' AND value='{$vl}'";
	}	
	
	if (mysql_query($query['val_do'],$connection))
	{ 	echo "<action>{$action}</action>"
			."<value_type>".xml_clean_simple($_GET['tp'])."</value_type>"
			."<value>".xml_clean_simple($_GET['vl'])."</value>"
			."<value_name>".xml_clean_simple($_GET['nm'])."</value_name>"
			;
	}
	else { echo "<action>0</action>"; }
}


echo "</editor>";

?>