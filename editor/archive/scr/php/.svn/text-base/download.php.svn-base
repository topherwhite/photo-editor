<?php

require_once("../../scr/inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$alb = mysqlclean($_GET,"alb",12,$connection);


	$qu = 	"SELECT {$path['db']}img.* FROM {$path['db']}img, {$path['db']}albms"
			." WHERE {$path['db']}albms.album='{$alb}'"
			." AND {$path['db']}img.time={$path['db']}albms.ref_time"
			." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
			." AND {$path['db']}img.deleted=0"
			." ORDER BY {$path['db']}img.time DESC";
			
	$data = mysql_query($qu,$connection);		
			
	mkdir("batch/{$alb}",0777);		
	if (file_exists("batch/{$alb}.tgz")) { unlink("batch/{$alb}.tgz"); }		
			
			
	for ($i = 0; $i < mysql_num_rows($data); $i++)
	{
		$img[$i] = mysql_fetch_array($data);
		
		$dst[$i] = "batch/{$alb}/{$img[$i]['creator']}.{$img[$i]['time']}.jpg";
		if (file_exists($dst[$i])) { unlink($dst[$i]); }
		
		$ex[$i] = 	"/usr/local/bin/convert"
				." {$path['bambi']}/draft/jpeg/".date("Y_m_d",$img[$i]['time'])."/{$img[$i]['creator']}.{$img[$i]['time']}.jpg"
				." -crop {$img[$i]['crop_wd']}x{$img[$i]['crop_ht']}+{$img[$i]['crop_x']}+{$img[$i]['crop_y']}"
	//			." jpg:- | "
	//			."/usr/local/bin/convert -"
	//			." -scale {$img_disp['size']['down']}x{$img_disp['size']['down']}"
				." {$dst[$i]}";
		
		exec($ex[$i]);
		
	}
	
	
	$ex['tar'] = "tar czf batch/{$alb}.tgz \"batch/{$alb}\"";
	exec($ex['tar']);
	
	for ($i = 0; $i < mysql_num_rows($data); $i++)
	{
		if (file_exists($dst[$i])) { unlink($dst[$i]); }
	}
	if (is_dir("batch/{$alb}")) { rmdir("batch/{$alb}"); }
	
	if (file_exists("batch/{$alb}.tgz")) { header("Location: batch/{$alb}.tgz?key=".mktime()); exit; }
}

?>
