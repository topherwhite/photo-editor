<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{	
	$tbls = explode("_",$_GET['fld']);
	$tbl = mysqlclean($tbls,0,25,$connection);
	$fld = mysqlclean($tbls,1,25,$connection);
	$out = mysqlclean($_GET,"val",200,$connection);
	$imgs = explode("-",$_GET['send']);
	$nmbr = intval($imgs[0]);
	$upd_usr = mysqlclean($_GET,"id",2,$connection);
	
	if ($nmbr > 0)
 	{	for ($i = 1; $i < count($imgs); $i++)
		{	
			$val[$i] = explode("_",$imgs[$i]);
		
			$query['do'] = "UPDATE {$path['db']}{$tbl} SET {$fld}='{$out}', {$fld}_time={$tm}"
							." WHERE creator='{$val[$i][0]}' AND time={$val[$i][1]}";
			
			if (mysql_query($query['do'],$connection)) { echo "<rtrn usr=\"{$val[$i][0]}\" wh=\"{$val[$i][1]}\" />"; }
			
		}
	}
	
	echo "<batch field=\"{$fld}\" value=\"{$out}\" nmbr=\"{$nmbr}\" />";
		
}

echo "</editor>";

?>