#!/usr/bin/php -q
<?php

require_once("../../inc/php/vars.inc.php");

$overwrite = 0;
$fld = scandir("{$var['path']['tmp']}/orig");

$i = 0;

foreach ($fld as $ind=>$val)
{	if (substr($val,0,1) != ".")
	{	$file = scandir("{$var['path']['tmp']}/orig/{$val}");
		foreach ($file as $ind_=>$val_)
		{	if (substr($val_,0,1) != ".")
			{
				$i++;
				echo "\n{$i}) {$val_}";
				
				if (	!file_exists("{$var['path']['tmp']}/jpeg/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")
					||	!file_exists("{$var['path']['tmp']}/work/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")
					||	!file_exists("{$var['path']['tmp']}/thmb/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")
					||	($overwrite == 1)
					)
				{
					
				if (!is_dir("{$var['path']['tmp']}/jpeg/".substr($val_,0,2))) { mkdir("{$var['path']['tmp']}/jpeg/".substr($val_,0,2)); }
				$mw = NewMagickWand();
				MagickReadImage($mw,"{$var['path']['tmp']}/orig/".substr($val_,0,2)."/{$val_}");
				MagickSetImageFormat($mw,"JPG");
				MagickSetImageCompression($mw, MW_JPEGCompression);
			//	if (($overwrite == 1) || (!file_exists("{$var['path']['tmp']}/jpeg/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")))
			//	{
					MagickSetImageCompressionQuality($mw, 100.0);
					MagickWriteImage($mw,"{$var['path']['tmp']}/jpeg/".substr($val_,0,2)."/".substr($val_,0,5).".jpg");
			//	}
				echo " -> jpeg";
				
				$size = GetImageSize("{$var['path']['tmp']}/jpeg/".substr($val_,0,2)."/".substr($val_,0,5).".jpg");
				if ($size[0] >= $size[1]) { $wd = $var['img']['work']['sq']; $ht = round(($var['img']['work']['sq']/$size[0])*$size[1]); }
				else { $ht = $var['img']['work']['sq']; $wd = round(($var['img']['work']['sq']/$size[1])*$size[0]); }
				
				if (!is_dir("{$var['path']['tmp']}/work/".substr($val_,0,2))) { mkdir("{$var['path']['tmp']}/work/".substr($val_,0,2)); }
			//	if (($overwrite == 1) || (!file_exists("{$var['path']['tmp']}/work/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")))
			//	{
					MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
					MagickSetImageCompressionQuality($mw, 80.0);
					MagickWriteImage($mw,"{$var['path']['tmp']}/work/".substr($val_,0,2)."/".substr($val_,0,5).".jpg");
			//	}
				echo " -> work";
				
				if (!is_dir("{$var['path']['tmp']}/thmb/".substr($val_,0,2))) { mkdir("{$var['path']['tmp']}/thmb/".substr($val_,0,2)); }			
				if ($size[0] >= $size[1]) { $wd = $var['img']['thmb']['sq']; $ht = round(($var['img']['thmb']['sq']/$size[0])*$size[1]); }
				else { $ht = $var['img']['thmb']['sq']; $wd = round(($var['img']['thmb']['sq']/$size[1])*$size[0]); }
			//	if (($overwrite == 1) || (!file_exists("{$var['path']['tmp']}/thmb/".substr($val_,0,2)."/".substr($val_,0,5).".jpg")))
			//	{
					MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
					MagickSetImageCompressionQuality($mw, 70.0);
					MagickWriteImage($mw,"{$var['path']['tmp']}/thmb/".substr($val_,0,2)."/".substr($val_,0,5).".jpg");
			//	}
				echo " -> thmb";
				
				DestroyMagickWand($mw);
				
				} else { echo " -> already done"; }
			}
		}
	}

}
/*
if ($con = mysql_conn($var))
{
	foreach($files as $ind=>$val)
	{	if (substr($val,0,1) != ".")
		{
			$ext = strtolower(strrev(substr(strrev($val),0,strpos(strrev($val),"."))));
		
			if (($ext != "xmp") && ($ext != "bib"))	
			{	
				$key = generate_unique_key($con,"editor.image","image_id",$key_length,1);
				
				echo "\n\n{$val} -> {$key}\n";
		
				//clear metadata array variable
				$meta = array("exif_raw"=>"","exif_json"=>"","xmp_json"=>"");
				
				// parse EXIF metadata into JSON string for database storage
				exec("/usr/bin/exiftool -tag -all {$var['path']['tmp']}/upld/{$val}",$meta['exif_raw']);
				$meta['exif_json'] = json_encode(exif_to_arr($meta['exif_raw']));  echo "exif (".strlen($meta['exif_json']).")";

				// if XMP metadata rider-file exists, parse it into JSON string for database storage and move the file out of upload directory		
				$xmp = strrev(substr(strrev($val),1+strpos(strrev($val),"."))).".xmp";
				if (file_exists("{$var['path']['tmp']}/upld/{$xmp}")) { $meta['xmp_json'] = json_encode(xml2array(file_get_contents("{$var['path']['tmp']}/upld/{$xmp}"))); rename("{$var['path']['tmp']}/upld/{$xmp}","{$var['path']['tmp']}/done/{$xmp}"); }
				echo " -> xmp (".strlen($meta['xmp_json']).")";
				
				// if BIB metadata rider-file exists, just move the file out of upload directory (perhaps should parse and store it)
				$bib = strrev(substr(strrev($val),1+strpos(strrev($val),"."))).".bib";
				if (file_exists("{$var['path']['tmp']}/upld/{$val}.bib")) { rename("{$var['path']['tmp']}/upld/{$val}.bib","{$var['path']['tmp']}/done/{$bib}"); }
				elseif (file_exists("{$var['path']['tmp']}/upld/{$bib}")) { rename("{$var['path']['tmp']}/upld/{$bib}","{$var['path']['tmp']}/done/{$bib}"); }
				
				
				if (copy("{$var['path']['tmp']}/upld/{$val}","{$var['path']['tmp']}/orig/{$key}.{$ext}"))
				{	echo " -> copy";
					if (report_success("{$var['path']['tmp']}/orig/{$key}.{$ext}"))
					{	if (rename("{$var['path']['tmp']}/upld/{$val}","{$var['path']['tmp']}/done/{$val}"))
						{	
							echo " -> db";
							if (mysql_query("INSERT INTO editor.image SET"
										." image_id='{$key}', created=".mktime()
										.", project_id='{$var['id']['project']}', origin_id='{$var['id']['origin']}'"
										.", orig_name='".mysqlclean($val,150,$con)."'"
										.", orig_format='".mysqlclean($ext,4,$con)."'"
										.", orig_exif='".mysqlclean($meta['exif_json'],64000,$con)."'"
										.", orig_xmp='".mysqlclean($meta['xmp_json'],64000,$con)."'"
										,$con)) { echo " (+)"; } else { echo " (-)"; }
							
							
							echo " -> jpeg";
							$mw = NewMagickWand();
							MagickReadImage($mw,"{$var['path']['tmp']}/orig/{$key}.{$ext}");
							MagickSetImageFormat($mw,"JPG");
							MagickSetImageCompression($mw, MW_JPEGCompression);
							MagickSetImageCompressionQuality($mw, 100.0);
							MagickWriteImage($mw,"{$var['path']['tmp']}/jpeg/{$key}.jpg");
							
							if (report_success("{$var['path']['tmp']}/jpeg/{$key}.jpg"))
							{	
								echo " -> work";
											
								$size = GetImageSize("{$var['path']['tmp']}/jpeg/{$key}.jpg");
								if ($size[0] >= $size[1]) { $wd = $var['img']['work']['sq']; $ht = round(($var['img']['work']['sq']/$size[0])*$size[1]); }
								else { $ht = $var['img']['work']['sq']; $wd = round(($var['img']['work']['sq']/$size[1])*$size[0]); }
								
								mysql_query("UPDATE editor.image SET width={$size[0]}, height={$size[1]} WHERE image_id='{$key}'",$con);
								
								MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
								MagickSetImageCompressionQuality($mw, 80.0);
								MagickWriteImage($mw,"{$var['path']['tmp']}/work/{$key}.jpg");
					
								if (report_success("{$var['path']['tmp']}/work/{$key}.jpg"))
								{	
									echo " -> thmb";
									
									if ($size[0] >= $size[1]) { $wd = $var['img']['thmb']['sq']; $ht = round(($var['img']['thmb']['sq']/$size[0])*$size[1]); }
									else { $ht = $var['img']['thmb']['sq']; $wd = round(($var['img']['thmb']['sq']/$size[1])*$size[0]); }
						
									MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
									MagickSetImageCompressionQuality($mw, 70.0);
									MagickWriteImage($mw,"{$var['path']['tmp']}/thmb/{$key}.jpg");
						
									if (report_success("{$var['path']['tmp']}/thmb/{$key}.jpg"))
									{
										mysql_query("UPDATE editor.image SET created_jpg=".mktime()." WHERE image_id='{$key}'",$con);	
							}	}	}
								
							DestroyMagickWand($mw);
				}	}	}		
	}	}	}
}
mysql_close($con);
*/

echo "\n";

?>
