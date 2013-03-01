#!/usr/bin/php -q
<?php

require_once("../../inc/php/vars.inc.php");






if ($con = mysql_conn($var))
{
	$qu = mysql_query("SELECT image_id FROM editor.image",$con);
	
	for ($i = 1; $i <= mysql_num_rows($qu); $i++)
	{	
		echo "\n{$i}) {$key}";
		$vals = mysql_fetch_array($qu); $key = $vals['image_id'];
		
		if (!is_dir("{$var['path']['tmp']}/1024/".substr($key,0,2))) { mkdir("{$var['path']['tmp']}/1024/".substr($key,0,2)); }
		
		if (!is_dir("{$var['path']['tmp']}/2048/".substr($key,0,2))) { mkdir("{$var['path']['tmp']}/2048/".substr($key,0,2)); }
		
		if (!is_dir("{$var['path']['tmp']}/4096/".substr($key,0,2))) { mkdir("{$var['path']['tmp']}/4096/".substr($key,0,2)); }
		
		if (!is_dir("{$var['path']['tmp']}/180/".substr($key,0,2))) { mkdir("{$var['path']['tmp']}/180/".substr($key,0,2)); }
		
		if (!file_exists("{$var['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"))
		{	if (file_exists("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg"))
			{	$size = GetImageSize("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
				if ($size[0] >= $size[1]) { $wd = $var['img']['1024']['sq']; $ht = round(($var['img']['1024']['sq']/$size[0])*$size[1]); }
				else { $ht = $var['img']['1024']['sq']; $wd = round(($var['img']['1024']['sq']/$size[1])*$size[0]); }
		
				$mw = NewMagickWand();
				MagickReadImage($mw,"{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
				MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
				MagickSetImageFormat($mw,"JPG");
				MagickSetImageCompression($mw, MW_JPEGCompression);
				MagickSetImageCompressionQuality($mw, 80.0);
				MagickWriteImage($mw,"{$var['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg");
				if (file_exists("{$var['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"))
				{ echo " -> 1024 created"; } else { " -> 1024 failed"; }
			}	else { echo " -> jpeg not there"; }
		}

		if (!file_exists("{$var['path']['tmp']}/4096/".substr($key,0,2)."/{$key}.jpg"))
		{	if (file_exists("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg"))
			{	$size = GetImageSize("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
				if ($size[0] >= $size[1]) { $wd = $var['img']['4096']['sq']; $ht = round(($var['img']['4096']['sq']/$size[0])*$size[1]); }
				else { $ht = $var['img']['4096']['sq']; $wd = round(($var['img']['4096']['sq']/$size[1])*$size[0]); }
		
				$mw = NewMagickWand();
				MagickReadImage($mw,"{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
				MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
				MagickSetImageFormat($mw,"JPG");
				MagickSetImageCompression($mw, MW_JPEGCompression);
				MagickSetImageCompressionQuality($mw, 80.0);
				MagickWriteImage($mw,"{$var['path']['tmp']}/4096/".substr($key,0,2)."/{$key}.jpg");
				if (file_exists("{$var['path']['tmp']}/4096/".substr($key,0,2)."/{$key}.jpg"))
				{ echo " -> 4096 created"; } else { " -> 4096 failed"; }
			}	else { echo " -> jpeg not there"; }
		}
		
	}
}


?>
