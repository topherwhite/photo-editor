<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../../scr/inc/var.inc.php");
require_once("../../scr/inc/xml_clean.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
//	$alb = mysqlclean($_GET,"alb",12,$connection);


	$qu = 	"SELECT {$path['db']}img.* FROM {$path['db']}img, {$path['db']}albms"
			." WHERE {$path['db']}img.status=1"
			." AND {$path['db']}img.time={$path['db']}albms.ref_time"
			." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
			." AND {$path['db']}img.deleted=0"
			." ORDER BY {$path['db']}img.time DESC";
			
			
//	die($qu);		
	$data = mysql_query($qu,$connection);		
			
	echo "\n <meta exported=\"".date("Y-m-d H:i:s",$tm)."\""
		//	." album_id=\"{$alb}\""
			.">";
		
	for ($i = 0; $i < mysql_num_rows($data); $i++)
	{
		$img[$i] = mysql_fetch_array($data);
		
		$cp[$i] = mysql_fetch_array(mysql_query("SELECT updated, caption, version_tag FROM {$path['db']}cptn WHERE usr='{$img[$i]['creator']}' AND time={$img[$i]['time']} AND versioned=0",$connection));
		$mt[$i] = mysql_fetch_array(mysql_query("SELECT * FROM {$path['db']}meta WHERE creator='{$img[$i]['creator']}' AND time={$img[$i]['time']}",$connection));		
		
		if (!empty($cp[$i]['caption']))
		{
			echo "\n  <photo"
				." id=\"{$img[$i]['creator']}.{$img[$i]['time']}\""
				." marked_for_export=\"".date("Y-m-d H:i:s",$img[$i]['status_time'])."\""
//				." last_updated=\"".date("Y-m-d H:i:s",$img[$i]['time'])."\""
				.">"
					."\n   <caption"
//						." version=\"\""
						." last_updated=\"".date("Y-m-d H:i:s",$cp[$i]['updated'])."\""
							.">"
							. xml_clean_simple($cp[$i]['caption'])
						."</caption>"
					."\n   <credit"
						." photographer=\"".xml_clean_simple($mt[$i]['aao_credit'])."\""
						." affiliation=\"".xml_clean_simple($mt[$i]['aao_src'])."\""
							." />"	
					."\n   <rating"
						." last_updated=\"".date("Y-m-d H:i:s",$img[$i]['rating_time'])."\""
						." value=\"".intval($img[$i]['rating'])."\""
							." />"					
					."\n  </photo>"
				;
				
			if ($_GET['upd'] == 1)
			{
	//			die("UPDATE {$path['db']}img SET status=2, status_time={$tm} WHERE usr='{$img[$i]['creator']}' AND time={$img[$i]['time']}");
				mysql_query("UPDATE {$path['db']}img SET status=2, status_time={$tm} WHERE creator='{$img[$i]['creator']}' AND time={$img[$i]['time']}",$connection);
			}	
		}
	}
	
	echo "\n </meta>";
}

echo "\n</editor>";
?>
