<?php


require_once("../../scr/inc/var.inc.php");

if ((!empty($_GET['usr'])) && (!empty($_GET['wh'])))
{
	if (empty($_FILES["hi_res_file"]['name']))
	{
		echo "<img src=\"{$path['img_srvr']}img/dynamic/thmb.{$_GET['usr']}.{$_GET['wh']}.jpg\" />"
			."<br /><br /><form id=\"hires_upload\" enctype=\"multipart/form-data\" method=\"post\" style=\"\""
					." action=\"?usr={$_GET['usr']}&wh={$_GET['wh']}&key=".mktime()."\">"
			."<input id=\"hi_res_file\" name=\"hi_res_file\" type=\"file\" />"
			."<br /><br /><input type=\"submit\" value=\"upload hi-res\" style=\"cursor:pointer;\" />"
			."</form>"
			;
		
	}
	elseif ($connection = @mysql_connect($srvr,$user,$pswd))
	{
		$usr = mysqlclean($_GET,"usr",2,$connection);
		$wh = mysqlclean($_GET,"wh",10,$connection);
		$orig_name = mysqlclean($_FILES["hi_res_file"],"name",100,$connection);

		$ext = strtolower(substr($_FILES["hi_res_file"]['name']
					,(0-strpos(strrev($_FILES["hi_res_file"]['name']),"."))));
					

		
		$path['final'] = "{$path['bambi']}/final/".date("Y_m_d",$wh)."/{$usr}.{$wh}.{$ext}";
//		$path['final'] = "{$path['bambi']}/final/{$usr}.{$wh}.{$ext}";
		
//		var_dump($_FILES);
		
		
//		die();
//		die($_FILES["hi_res_file"]['tmp_name']);
		
		if (!file_exists("{$path['bambi']}/final/".date("Y_m_d",$wh))) { mkdir("{$path['bambi']}/final/".date("Y_m_d",$wh),0777); }
		
		if (file_exists($path['final'])) { unlink($path['final']); }

		if (!(move_uploaded_file($_FILES["hi_res_file"]['tmp_name'],$path['final'])))
		{	unlink($_FILES["hi_res_file"]['tmp_name']);
			die("failure - file wasn't moved ({$_FILES["hi_res_file"]['tmp_name']}) ({$path['final']})");
//			header("Location: /?msg=file_save_err");
//			exit;
		}
		elseif (	mysql_query("UPDATE {$path['db']}img SET hires_added={$tm} WHERE creator='{$usr}' AND time={$wh}",$connection)
				&&	mysql_query("UPDATE {$path['db']}meta SET hires_origname='{$orig_name}' WHERE creator='{$usr}' AND time={$wh}",$connection)
				)
		{
			die("<br /><br />{$path['final']}<br /><br />upload successful...<br /><br /><input type=\"button\" value=\"click to close window\" style=\"cursor:pointer;\" onClick=\"window.close()\" />");
		
		}
	}

}





?>
