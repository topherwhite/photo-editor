<?php

require_once("/liz/www/archive/scr/inc/read_write.inc.php");
require_once("/liz/www/archive/scr/inc/var.inc.php");

$val = explode(".",substr( $url_dir,(0-strpos(strrev($url_dir),"/"))));

if (	($val[0] == "thmb")
	||	($val[0] == "view")
	||	($val[0] == "work")
	||	($val[0] == "down")
	||	($val[0] == "orig")
	||	($val[0] == "hres")
	)
{	
	$img = substr(strval($val[0]),0,4);
	$usr = substr(strval($val[1]),0,2);
	$wh = intval($val[2]);
	
	header('Content-Type: image/jpeg');
		
	if (($img == "orig") || ($img == "work") || ($img == "hres"))
	{	
		if ($img == "orig")		{ $outer_dir = "draft/jpeg"; $ext = "jpg"; }
		elseif ($img == "work")	{ $outer_dir = "draft/work"; $ext = "jpg"; }
		elseif ($img == "hres")
		{	$qu = "SELECT hires_origname, hires_added, orig_format  FROM {$path['db']}meta, {$path['db']}img"
					." WHERE meta.time=img.time AND meta.creator=img.creator AND img.creator='{$usr}' AND img.time={$wh}";
			if ( ($connection = @mysql_connect($srvr,$user,$pswd)) && ($info = mysql_fetch_array(mysql_query($qu,$connection))) )
			{	
				if ($info['hires_added'] == "1111111111") { die("unadded"); }	
				elseif ($info['hires_added'] == "1234567890") { $outer_dir = "draft/orig"; $ext = $info['orig_format']; }
				else { $outer_dir = "final"; $ext = strtolower(substr($info['hires_origname'],(0-strpos(strrev($info['hires_origname']),".")))); }
				mysql_close($connection);
			}
		}
				
		echo read_file("s3","{$path['bambi']}/{$outer_dir}/".date("Y_m_d",$wh)."/{$usr}.{$wh}.{$ext}");					
	}

	else
	{
		if ($connection = @mysql_connect($srvr,$user,$pswd))
		{	
			$info = mysql_fetch_array(
					mysql_query("SELECT * FROM {$path['db']}img WHERE creator='{$usr}' AND time={$wh}",$connection));
			mysql_close($connection);
				
			if ($img != "down")
			{	$src = "work";
				if ($info['orig_wd'] >= $info['orig_ht'])
				{ $conv = $img_disp['size']['work']/$info['orig_wd']; }
				else
				{ $conv = $img_disp['size']['work']/$info['orig_ht']; }
			}
			else
			{	$conv = 1; $src = "jpeg"; 
			}
			
			$in = array(	'wd' => round($info['crop_wd']*$conv),'ht' => round($info['crop_ht']*$conv)
							,'lf' => round($info['crop_x']*$conv),'tp' => round($info['crop_y']*$conv)
							,'src' => "{$path['bambi']}/draft/{$src}/".date("Y_m_d",$wh)."/{$usr}.{$wh}.jpg"		);
	
			
			if ($info['crop_wd'] >= $info['crop_ht'])
			{ 	$out = array(	'wd' => $img_disp['size'][$img]
								,'ht' => round(($info['crop_ht']/$info['crop_wd'])*$img_disp['size'][$img])		);
			}
			else
			{ 	$out = array( 	'ht' => $img_disp['size'][$img]
								,'wd' => round(($info['crop_wd']/$info['crop_ht'])*$img_disp['size'][$img])		);
			}
			
			$final_img = ImageCreateTrueColor($out['wd'],$out['ht']);
			ImageCopyResampled( $final_img, ImageCreateFromString(read_file("s3",$in['src']))	
								, 0, 0, $in['lf'], $in['tp'], $out['wd'], $out['ht'], $in['wd'], $in['ht']	);
			ImageJPEG($final_img,"",$img_disp['qual'][$img]);			
			ImageDestroy($final_img);
		}
	}
}



?>
