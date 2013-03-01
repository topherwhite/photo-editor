<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");
require_once("../inc/xml_clean.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$ch = explode("-",mysqlclean($_GET,"ch",1024,$connection));
	
	for ($i = 1; $i < (count($ch)-1); $i++)
	{	$id[$i] = explode(".",substr($_GET[$ch[$i]],0,strpos($_GET[$ch[$i]],"_")));
		$txt[$i] = substr($_GET[$ch[$i]],strpos($_GET[$ch[$i]],"_")+1);
		
		$test[$i] = mysql_fetch_array(mysql_query(
				"SELECT rank FROM {$path['db']}cptn WHERE usr='{$id[$i][0]}' AND time={$id[$i][1]} AND versioned=0 ORDER BY updated DESC LIMIT 1"
				,$connection));
		
		if (empty($test[$i]['rank']))
		{	mysql_query("INSERT INTO {$path['db']}cptn SET usr='{$id[$i][0]}', time={$id[$i][1]}, updated={$tm}",$connection);
			$test[$i] = mysql_fetch_array(mysql_query(
				"SELECT rank FROM {$path['db']}cptn WHERE usr='{$id[$i][0]}' AND time={$id[$i][1]} AND versioned=0"
				,$connection));
		}
		
		if (mysql_query("UPDATE {$path['db']}cptn SET"
					." caption='".mysqlclean($txt,$i,1500,$connection)."', updated={$tm} WHERE rank={$test[$i]['rank']}",$connection))
		{	echo "<cptn success=\"1\" usr=\"{$id[$i][0]}\" time=\"{$id[$i][1]}\">"
					.xml_clean_simple($txt[$i])
				."</cptn>";
		}
		else
		{	echo "<cptn success=\"0\" usr=\"{$id[$i][0]}\" time=\"{$id[$i][1]}\"></cptn>";	
		}
	}
	
}

echo "</editor>";

?>