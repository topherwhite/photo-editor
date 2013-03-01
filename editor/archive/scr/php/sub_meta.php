<?php

require_once("../../scr/inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$usr = mysqlclean($_GET,"usr",2,$connection);
	$wh = mysqlclean($_GET,"wh",10,$connection);

	$query['ins'] = "UPDATE {$path['db']}meta SET written={$tm}";

	for ($i = 0; $i < count($req); $i++)
	{
		$ind = "aao_{$req[$i]}";
		$val[$i] = mysqlclean($_POST,$ind,1000,$connection);
		if (!empty($val[$i])) { $query['ins'] .= ", {$ind}='{$val[$i]}'"; }
	}
	$query['ins'] .= " WHERE creator='{$usr}' AND time={$wh}";

	if (!mysql_query($query['ins'],$connection))
	{
		die($query['ins']);
	}
	else
	{
		header("Location: /");
		exit;
	}
}

?>
