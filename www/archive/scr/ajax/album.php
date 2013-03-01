<?php

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$alb = mysqlclean($_GET,"alb",12,$connection);
	$do = intval($_GET['do']);
	$imgs = explode("-",$_GET['send']);
	$nmbr = intval($imgs[0]);
	$upd_usr = mysqlclean($_GET,"id",2,$connection);
	
	$deja = 0; $fail = 0; $succ = 0;
	
 if ($nmbr > 0)
 {	for ($i = 1; $i < count($imgs); $i++)
	{	
		$val[$i] = explode("_",$imgs[$i]);
		
		if ($do == 1)
		{	$query['check'] = "SELECT album, upd_user FROM {$path['db']}albms"
					." WHERE album='{$alb}' AND ref_user='{$val[$i][0]}' AND ref_time={$val[$i][1]} LIMIT 1";
			$chk = mysql_fetch_array(mysql_query($query['check'],$connection));
		}
		
		if (($do == 1) && !empty($chk['album'])) { $deja++; }
		else
		{	
			if ($do == 1)
			{	$query['do'] = "INSERT INTO {$path['db']}albms SET album='{$alb}'"
								.", ref_user='{$val[$i][0]}', ref_time={$val[$i][1]}"
								.", upd_user='{$upd_usr}', upd_time={$tm}";
			}
			elseif ($do == 2)
			{	$query['do'] = "DELETE FROM {$path['db']}albms WHERE album='{$alb}'"
								." AND ref_user='{$val[$i][0]}' AND ref_time={$val[$i][1]}";
								
			}
			
			if (mysql_query($query['do'],$connection)) { $succ++; }
			else { $fail++; }
			
		}		
	}
 }	
	echo "<do>{$do}</do>"
		."<album>{$alb}</album>"
		."<nmbr>{$nmbr}</nmbr>"
		."<succ>{$succ}</succ>"
		."<fail>{$fail}</fail>"
		."<deja>{$deja}</deja>";
		
}

echo "</editor>";

?>