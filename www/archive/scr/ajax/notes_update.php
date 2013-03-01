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
		
		if (mysql_query("UPDATE {$path['db']}meta SET"
					." aao_notes='".mysqlclean($txt,$i,3000,$connection)."' WHERE creator='{$id[$i][0]}' AND time={$id[$i][1]}",$connection))
		{	echo "<notes success=\"1\" usr=\"{$id[$i][0]}\" time=\"{$id[$i][1]}\">"
					.xml_clean_simple($txt[$i])
				."</notes>";
		}
		else
		{	echo "<notes success=\"0\" usr=\"{$id[$i][0]}\" time=\"{$id[$i][1]}\"></notes>";	
		}
	}
	
}

echo "</editor>";

?>