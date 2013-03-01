<?php

require_once("../../inc/php/vars.inc.php");

if (!empty($url_arr[2]))
{
	$params = explode("_",$url_arr[2]);
	$img = $params[0]; $wh = $params[1];
	if (count($params) == 4) { $row = intval($params[2]); $col = intval($params[3]); }

	$img_file = "{$var['path']['tmp']}/{$wh}/".substr($img,0,2)."/{$img}.jpg";
	
	if (file_exists($img_file))
	{	if (count($params) < 4) { header('Content-Type: image/jpeg'); echo file_get_contents($img_file); }
		else
		{	$tile_sq = 512;
			$img_rsc = @ImageCreateFromJPEG($img_file); $rsc_wd = ImageSx($img_rsc); $rsc_ht = ImageSy($img_rsc);
			
			if 	(	($tile_sq*$row >= 0) && ($tile_sq*$col >= 0)
				&&	($tile_sq*$row < $rsc_wd) && ($tile_sq*$col < $rsc_ht)
				)
			{	$out_wd = $tile_sq; if ( ($rsc_wd-$tile_sq*$row) < $tile_sq) { $out_wd = $rsc_wd-$tile_sq*$row; }
				$out_ht = $tile_sq; if ( ($rsc_ht-$tile_sq*$col) < $tile_sq) { $out_ht = $rsc_ht-$tile_sq*$col; }
				$img_out = ImageCreateTrueColor($out_wd,$out_ht);
				ImageCopy($img_out,$img_rsc,0,0,$tile_sq*$row,$tile_sq*$col,$out_wd,$out_ht);
				header('Content-Type: image/jpeg'); ImageJPEG($img_out,NULL,70);
			}
			else { header("{$_SERVER["SERVER_PROTOCOL"]} 404 Not Found"); }
		}
	}
}


?>