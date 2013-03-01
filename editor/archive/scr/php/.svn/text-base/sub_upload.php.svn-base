<?php

require_once("../../scr/inc/var.inc.php");

//session_start();

if (!($connection = @mysql_connect($srvr,$user,$pswd))) { die("Could not make database connection."); }

$dt = date("Y_m_d",$tm);

$usr = "rs";

$_SESSION['usr'] = $usr;

$path['orig'] = "{$path['bambi']}/draft/orig/{$dt}";
$path['jpeg'] = "{$path['bambi']}/draft/jpeg/{$dt}";
$path['work'] = "{$path['bambi']}/draft/work/{$dt}";
$path['final'] = "{$path['bambi']}/final/{$dt}";

$path['tmp'] = $_FILES["photo_upload_file"]['tmp_name'];

if (!file_exists($path['orig'])) { mkdir($path['orig'],0777); mkdir($path['jpeg'],0777); mkdir($path['work'],0777); mkdir($path['final'],0777); }


$ext = strtolower(substr($_FILES["photo_upload_file"]['name']
					,(0-strpos(strrev($_FILES["photo_upload_file"]['name']),"."))));

$file['orig'] = "{$path['orig']}/{$usr}.{$tm}.{$ext}";
$file['jpeg'] = "{$path['jpeg']}/{$usr}.{$tm}.jpg";
$file['work'] = "{$path['work']}/{$usr}.{$tm}.jpg";


	
	if (empty($_FILES["photo_upload_file"]['name']))
	{	unlink($path['tmp']);
		header("Location: /?msg=badfile");
		exit;
	}
	else
	{	if (!(move_uploaded_file($path['tmp'],$file['orig'])))
		{	unlink($path['tmp']);
	//		die($file['orig']);
			header("Location: /?msg=file_save_err");
			exit;
		}
		else
		{
			$cmd['conv_jpeg'] = "/usr/local/bin/convert {$file['orig']} {$file['jpeg']}";
			exec($cmd['conv_jpeg']);
			
			$cmd['conv_work'] = "/usr/local/bin/convert -scale {$img_disp['size']['work']}x{$img_disp['size']['work']} {$file['orig']} {$file['work']}";
			exec($cmd['conv_work']);	
		}
		
		
		if (!file_exists($file['jpeg']) || !file_exists($file['work']))
		{
			header("Location: /?msg=file_conv_err");
			exit;
		}
	
		else
		{
			$cmd['get_meta'] = "/usr/bin/exiftool -tag -all {$file['orig']}";
			
			exec($cmd['get_meta'],$cmd['return']);	
			
			for ($c = 0; $c < count($cmd['return']);$c++)
			{
				$cmd['pos'][$c] = strpos($cmd['return'][$c],":");
				$cmd['tag'][$c] = trim(substr($cmd['return'][$c],0,$cmd['pos'][$c]));
				$cmd['dat'][$c] = trim(substr($cmd['return'][$c],$cmd['pos'][$c]));
				
				$cmd['pre_meta'][$cmd['tag'][$c]] = $cmd['dat'][$c];
				$cmd['filename'] = $_FILES["photo_upload_file"]['name'];
			}
				
			$cmd['metadata'] = serialize($cmd['pre_meta']);
				
			$query['meta'] = "INSERT INTO {$path['db']}meta SET"
					." creator='{$usr}', time={$tm}"
					.", orig_meta=\"".mysqlclean($cmd,"metadata",5000,$connection)."\""
					.", orig_filename=\"".mysqlclean($cmd,"filename",100,$connection)."\""
					;
		
			$orig_dim = GetImageSize($file['jpeg']);
	//		$work_dim = GetImageSize($file['work']);			

			$query['img'] = "INSERT INTO {$path['db']}img SET"
					." creator='{$usr}', time={$tm}"
					.", orig_format='{$ext}', orig_wd={$orig_dim[0]}, orig_ht={$orig_dim[1]}"
			//		.", work_wd={$work_dim[0]}, work_ht={$work_dim[1]}"
					.", crop_wd={$orig_dim[0]}, crop_ht={$orig_dim[1]}"
					.", crop_x=0, crop_y=0"
					;

					
			if	(	mysql_query($query['img'],$connection)
				&&	mysql_query($query['meta'],$connection)
				)
			{
				
				header("Location: /metadata?wh={$tm}");
				exit;
			}	
					

		}
	}

//header("Location: {$base_url}upload?usr={$usr}&msg=misc");
//exit;



?>
