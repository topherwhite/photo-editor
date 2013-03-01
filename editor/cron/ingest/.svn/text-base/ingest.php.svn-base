#!/usr/bin/php -q
<?php

require_once("../../inc/php/vars.inc.php");
//require_once("../../inc/php/rand.inc.php");
//require_once("../../inc/php/xml2array.inc.php");



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
				
				if (!is_dir("{$var['path']['tmp']}/orig/".substr($key,0,2)))
				{	mkdir("{$var['path']['tmp']}/orig/".substr($key,0,2));
					mkdir("{$var['path']['tmp']}/jpeg/".substr($key,0,2));
					mkdir("{$var['path']['tmp']}/180/".substr($key,0,2));
					mkdir("{$var['path']['tmp']}/2048/".substr($key,0,2));
					mkdir("{$var['path']['tmp']}/1024/".substr($key,0,2));
				}
				
				if (copy("{$var['path']['tmp']}/upld/{$val}","{$var['path']['tmp']}/orig/".substr($key,0,2)."/{$key}.{$ext}"))
				{	echo " -> copy";
					if (report_success("{$var['path']['tmp']}/orig/".substr($key,0,2)."/{$key}.{$ext}"))
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
							MagickReadImage($mw,"{$var['path']['tmp']}/orig/".substr($key,0,2)."/{$key}.{$ext}");
							MagickSetImageFormat($mw,"JPG");
							MagickSetImageCompression($mw, MW_JPEGCompression);
							MagickSetImageCompressionQuality($mw, 100.0);
							MagickWriteImage($mw,"{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
							
							if (report_success("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg"))
							{	
								echo " -> 2048";
											
								$size = GetImageSize("{$var['path']['tmp']}/jpeg/".substr($key,0,2)."/{$key}.jpg");
								if ($size[0] >= $size[1]) { $wd = $var['img']['2048']['sq']; $ht = round(($var['img']['2048']['sq']/$size[0])*$size[1]); }
								else { $ht = $var['img']['2048']['sq']; $wd = round(($var['img']['2048']['sq']/$size[1])*$size[0]); }
								
								mysql_query("UPDATE editor.image SET width={$size[0]}, height={$size[1]} WHERE image_id='{$key}'",$con);
								
								MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
								MagickSetImageCompressionQuality($mw, 80.0);
								MagickWriteImage($mw,"{$var['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg");
					
								if (report_success("{$var['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg"))
								{	
									
									echo " -> 1024";
											
									if ($size[0] >= $size[1]) { $wd = $var['img']['1024']['sq']; $ht = round(($var['img']['1024']['sq']/$size[0])*$size[1]); }
									else { $ht = $var['img']['1024']['sq']; $wd = round(($var['img']['1024']['sq']/$size[1])*$size[0]); }
						
									MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
									MagickSetImageCompressionQuality($mw, 80.0);
									MagickWriteImage($mw,"{$var['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg");
									
									if (report_success("{$var['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"))
									{
									
										echo " -> 180";
									
										if ($size[0] >= $size[1]) { $wd = $var['img']['180']['sq']; $ht = round(($var['img']['180']['sq']/$size[0])*$size[1]); }
										else { $ht = $var['img']['180']['sq']; $wd = round(($var['img']['180']['sq']/$size[1])*$size[0]); }
						
										MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
										MagickSetImageCompressionQuality($mw, 70.0);
										MagickWriteImage($mw,"{$var['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg");
						
										if (report_success("{$var['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg"))
										{
										mysql_query("UPDATE editor.image SET created_jpg=".mktime()." WHERE image_id='{$key}'",$con);	
							}	}	}	}
								
							DestroyMagickWand($mw);
				}	}	}		
	}	}	}
}
mysql_close($con);


echo "\n";

?>
