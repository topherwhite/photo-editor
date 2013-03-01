<?php

function process_file_list($file,$upld_dir,$user_dir,$var,$con)
{	
	$script_timeout = 45;
	$minimum_file_age = 30;
		
	if	(	// ensures that script never runs for longer than a minute or so. this semi-ensures that a cron job running every minute won't overlap... (right?)
			((mktime()-$var['tm']) <= $script_timeout)
			// ensures that only files whose modification dates are older than the above state "minimum file age" will be processed...
		&&	((mktime()-filemtime("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}")) >= $minimum_file_age)
		)
	{	$ext = trim(strtolower(strrev(substr(strrev($file),0,strpos(strrev($file),".")))));
		// list of all file-extensions compatible with ImageMagick (MagickWand). incompatible files are 'discarded'
		if	(	($ext == 'art') || ($ext == 'arw') || ($ext == 'avi') || ($ext == 'avs') || ($ext == 'bmp') || ($ext == 'cals') || ($ext == 'cgm')
		||	($ext == 'cin') || ($ext == 'cmyk') || ($ext == 'cmyka') || ($ext == 'cr2') || ($ext == 'crw') || ($ext == 'cur') || ($ext == 'cut')
		||	($ext == 'dcm') || ($ext == 'dcr') || ($ext == 'dcx') || ($ext == 'dib') || ($ext == 'djvu') || ($ext == 'dng') || ($ext == 'dot')
		||	($ext == 'dpx') || ($ext == 'emf') || ($ext == 'epdf') || ($ext == 'epi') || ($ext == 'eps') || ($ext == 'eps2') || ($ext == 'eps3')
		||	($ext == 'epsf') || ($ext == 'epsi') || ($ext == 'ept') || ($ext == 'exr') || ($ext == 'fax') || ($ext == 'fig') || ($ext == 'fits')
		||	($ext == 'fpx') || ($ext == 'gif') || ($ext == 'gplt') || ($ext == 'gray') || ($ext == 'hpgl') || ($ext == 'hrz') || ($ext == 'html')
		||	($ext == 'ico') || ($ext == 'info') || ($ext == 'inline') || ($ext == 'jbig') || ($ext == 'jng') || ($ext == 'jp2') || ($ext == 'jpc')
		||	($ext == 'jpeg') || ($ext == 'man') || ($ext == 'mat') || ($ext == 'miff') || ($ext == 'mono') || ($ext == 'mng') || ($ext == 'm2v')
		||	($ext == 'mpeg') || ($ext == 'mpc') || ($ext == 'mpr') || ($ext == 'mrw') || ($ext == 'msl') || ($ext == 'mtv') || ($ext == 'mvg')
		||	($ext == 'nef') || ($ext == 'orf') || ($ext == 'otb') || ($ext == 'p7') || ($ext == 'palm') || ($ext == 'pam') || ($ext == 'pbm')
		||	($ext == 'pcd') || ($ext == 'pcds') || ($ext == 'pcl') || ($ext == 'pcx') || ($ext == 'pdb') || ($ext == 'pdf') || ($ext == 'pef')
		||	($ext == 'pfa') || ($ext == 'pfb') || ($ext == 'pfm') || ($ext == 'pgm') || ($ext == 'picon') || ($ext == 'pict') || ($ext == 'pix')
		||	($ext == 'png') || ($ext == 'png8') || ($ext == 'png24') || ($ext == 'png32') || ($ext == 'pnm') || ($ext == 'ppm') || ($ext == 'ps')
		||	($ext == 'ps2') || ($ext == 'ps3') || ($ext == 'psb') || ($ext == 'psd') || ($ext == 'ptif') || ($ext == 'pwp') || ($ext == 'rad')
		||	($ext == 'raf') || ($ext == 'rgb') || ($ext == 'rgba') || ($ext == 'rla') || ($ext == 'rle') || ($ext == 'sct') || ($ext == 'sfw')
		||	($ext == 'sgi') || ($ext == 'shtml') || ($ext == 'sid') || ($ext == 'sun') || ($ext == 'svg') || ($ext == 'tga') || ($ext == 'tiff')
		||	($ext == 'tim') || ($ext == 'ttf') || ($ext == 'uil') || ($ext == 'uyvy') || ($ext == 'vicar') || ($ext == 'viff')
		||	($ext == 'wbmp') || ($ext == 'wmf') || ($ext == 'wpg') || ($ext == 'x') || ($ext == 'xbm') || ($ext == 'xcf') || ($ext == 'xpm')
		||	($ext == 'xwd') || ($ext == 'x3f') || ($ext == 'ycbcr') || ($ext == 'ycbcra') || ($ext == 'yuv')
		// not included in ImageMagick standard definition, but seem to work (added manually)
		|| ($ext == 'jpg') || ($ext == 'tif') || ($ext == 'raw')
		// metadata files
		||	($ext == 'xmp') || ($ext == 'bib')
		)	{ ingest_img($file,$upld_dir,$user_dir,$var,$con); }
		else { discard_file($file,$upld_dir,$user_dir,$var); }
	}
	elseif ((mktime()-$var['tm']) > $script_timeout) { die("\nScript may only run for {$script_timeout}-60 seconds. Terminating..."); }
}

function create_incompatible_directory($upld_dir,$user_dir,$vars)
{	
	$owner = array("upld_web"=>"apache","upld_webdav"=>"apache","upld_vsftpd"=>"ftp");
	if (!is_dir("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files"))
	{	mkdir("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files");
		chown("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files",$owner[$upld_dir]);
		chgrp("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files",$owner[$upld_dir]);
	}
}

function discard_file($file,$upld_dir,$user_dir,$vars)
{	
	create_incompatible_directory($upld_dir,$user_dir,$vars);
	rename(	"{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}"
			,"{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files/".str_replace("/","-",$file));
	echo "\n{$file} > bad file type... moved to '-incompatible-files' directory";
}


function exif_to_arr($input)
{	$out = array();
	foreach ($input as $ind=>$val) { $out[trim(substr($val,0,strpos($val,":")))] = trim(substr($val,1+strpos($val,":"))); }
	return $out;
}

function report_success($path)
{	if (file_exists($path))
	{	echo " (+)";
		return true;
	}
}


function ingest_img($file,$upld_dir,$user_dir,$vars,$mysql_con)
{
	$ext = trim(strtolower(strrev(substr(strrev($file),0,strpos(strrev($file),".")))));
	
	$user_info = mysql_fetch_array(mysql_query("SELECT * FROM editor.user WHERE email='{$user_dir}'",$mysql_con));
	$user_id = $user_info['user_id'];
	$project_id = $user_info['project_id'];
	
	if (($ext != "xmp") && ($ext != "bib"))	
	{	$key = generate_unique_key($mysql_con,"editor.image","image_id",$vars['data']['key_length'],1);
		
		if (!is_dir("{$vars['path']['tmp']}/temp/{$key}")) { mkdir("{$vars['path']['tmp']}/temp/{$key}"); }
				
		echo "\n\n{$file} > {$key}";
		
		//clear metadata array variable
		$meta = array("exif_raw"=>"","exif_json"=>"","xmp_json"=>"");
				
		// parse EXIF metadata into JSON string for database storage
		exec("/usr/bin/exiftool -tag -all \"{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}\"",$meta['exif_raw']);
		$meta['exif_json'] = json_encode(exif_to_arr($meta['exif_raw']));  echo " > exif (".strlen($meta['exif_json']).")";

		// if XMP metadata rider-file exists, parse it into JSON string for database storage and move the file out of upload directory		
		$xmp = strrev(substr(strrev($file),1+strpos(strrev($file),"."))).".xmp";
		if (file_exists("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$xmp}"))
		{	$meta['xmp_json'] = json_encode(xml2array(file_get_contents("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$xmp}")));
			rename("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$xmp}","{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp");
		}
		echo " > xmp (".strlen($meta['xmp_json']).")";
				
		// if BIB metadata rider-file exists, just move the file out of upload directory (perhaps should parse and store it)
		$bib = strrev(substr(strrev($file),1+strpos(strrev($file),"."))).".bib";
		if (file_exists("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}.bib"))
		{	rename("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}.bib","{$vars['path']['tmp']}/temp/{$key}/{$bib}");
		}
		elseif (file_exists("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$bib}"))
		{	rename("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$bib}","{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib");
		}
		
		// create image cache directories...
		if (!is_dir("{$vars['path']['tmp']}/2048/".substr($key,0,2)))	{ mkdir("{$vars['path']['tmp']}/2048/".substr($key,0,2)); }
		if (!is_dir("{$vars['path']['tmp']}/1024/".substr($key,0,2)))	{ mkdir("{$vars['path']['tmp']}/1024/".substr($key,0,2)); }
		if (!is_dir("{$vars['path']['tmp']}/180/".substr($key,0,2)))	{ mkdir("{$vars['path']['tmp']}/180/".substr($key,0,2)); }
				
		if (rename("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file}","{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}"))
		{	
			echo " > copy";
			if (report_success("{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}"))
			{
				echo " > db";
				
				$ingestion_bucket = ""; $ingestion_bucket_length = 0;
				if (strpos($file,"/")) { $ingestion_bucket = strrev(substr(strrev($file),1+intval(strpos(strrev($file),"/")))); $ingestion_bucket_length = strlen($ingestion_bucket)+1; }
				
				if (mysql_query("INSERT INTO editor.image SET"
						." image_id='{$key}'"
						.", project_id='{$project_id}', origin_id='{$vars['id']['origin']}'"
						.", ingestion_user='{$user_id}'"
						.", ingestion_date=".mktime()
						.", ingestion_bucket='".mysqlclean($ingestion_bucket,150,$mysql_con)."'"
						.", orig_name='".mysqlclean(substr($file,$ingestion_bucket_length),150,$mysql_con)."'"
						.", orig_format='".mysqlclean($ext,4,$mysql_con)."'"
						.", orig_exif='".mysqlclean($meta['exif_json'],64000,$mysql_con)."'"
						.", orig_xmp='".mysqlclean($meta['xmp_json'],64000,$mysql_con)."'"
						,$mysql_con)) { echo " (+)"; } else { echo " (-)"; }
						
				echo " > jpeg";
				$mw = NewMagickWand();
				if ($ext == "pdf") { MagickSetResolution($mw,300,300); }
				MagickReadImage($mw,"{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}");
				MagickSetImageFormat($mw,"JPG");
				MagickSetImageCompression($mw, MW_JPEGCompression);
				MagickSetImageCompressionQuality($mw, 100.0);
				MagickWriteImage($mw,"{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg");
						
				if (report_success("{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg"))
				{	
					echo " > 2048";
									
					$size = GetImageSize("{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg");
					if ($size[0] >= $size[1]) { $wd = $vars['img']['2048']['sq']; $ht = round(($vars['img']['2048']['sq']/$size[0])*$size[1]); }
					else { $ht = $vars['img']['2048']['sq']; $wd = round(($vars['img']['2048']['sq']/$size[1])*$size[0]); }
							
					mysql_query("UPDATE editor.image SET width={$size[0]}, height={$size[1]} WHERE image_id='{$key}'",$mysql_con);
							
					MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
					MagickSetImageCompressionQuality($mw, 80.0);
					MagickWriteImage($mw,"{$vars['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg");
				
					if (report_success("{$vars['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg"))
					{	
						echo " > 1024";
									
						if ($size[0] >= $size[1]) { $wd = $vars['img']['1024']['sq']; $ht = round(($vars['img']['1024']['sq']/$size[0])*$size[1]); }
						else { $ht = $vars['img']['1024']['sq']; $wd = round(($vars['img']['1024']['sq']/$size[1])*$size[0]); }
					
						MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
						MagickSetImageCompressionQuality($mw, 80.0);
						MagickWriteImage($mw,"{$vars['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg");
								
						if (report_success("{$vars['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"))
						{
							echo " > 180";
								
							if ($size[0] >= $size[1]) { $wd = $vars['img']['180']['sq']; $ht = round(($vars['img']['180']['sq']/$size[0])*$size[1]); }
							else { $ht = $vars['img']['180']['sq']; $wd = round(($vars['img']['180']['sq']/$size[1])*$size[0]); }
					
							MagickResizeImage($mw, $wd, $ht, MW_QuadraticFilter, 1.0);
							MagickSetImageCompressionQuality($mw, 70.0);
							MagickWriteImage($mw,"{$vars['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg");
					
							if (report_success("{$vars['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg"))
							{	
								mysql_query("UPDATE editor.image SET created_jpg=".mktime()." WHERE image_id='{$key}'",$mysql_con);
								
								echo " > s3 orig"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}"
									,"aaoeditor/orig/".substr($key,0,2)."/{$key}.{$ext}");
								unlink("{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}");
								
								if (file_exists("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp"))
								{	echo ", xmp"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp"
									,"aaoeditor/orig/".substr($key,0,2)."/{$key}.xmp");
								unlink("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp");
								}
								
								if (file_exists("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib"))
								{	echo ", bib"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib"
									,"aaoeditor/orig/".substr($key,0,2)."/{$key}.bib");
								unlink("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib");
								}
								
								echo ", jpeg"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg"
									,"aaoeditor/jpeg/".substr($key,0,2)."/{$key}.jpg");
								unlink("{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg");
								
								rmdir("{$vars['path']['tmp']}/temp/{$key}");
								
								echo ", 2048"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg"
									,"aaoeditor/2048/".substr($key,0,2)."/{$key}.jpg");
								
								echo ", 1024"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"
									,"aaoeditor/1024/".substr($key,0,2)."/{$key}.jpg");
								
								echo ", 180"; write_file(	"s3",$vars['creds']
									,"{$vars['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg"
									,"aaoeditor/180/".substr($key,0,2)."/{$key}.jpg");
				}	}	}	}
				else {	backtrack_on_fail($key,$file,$upld_dir,$ext,$user_dir,$vars); }
			DestroyMagickWand($mw);
		}	}		
	}		
}

function backtrack_on_fail($key,$file,$upld_dir,$ext,$user_dir,$vars)
{	
	echo " (-) > removing image";
	$owner = array("upld_web"=>"apache","upld_webdav"=>"apache","upld_vsftpd"=>"ftp");
	if (!is_dir("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-failed-to-ingest"))
	{	mkdir("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-failed-to-ingest");
		chown("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-failed-to-ingest",$owner[$upld_dir]);
		chgrp("{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-failed-to-ingest",$owner[$upld_dir]);
	}
	if (file_exists("{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}"))
	{ rename("{$vars['path']['tmp']}/temp/{$key}/orig_{$key}.{$ext}","{$vars['path']['tmp']}/{$upld_dir}/{$user_dir}/-failed-to-ingest/".str_replace("/","-",$file)); }
	if (file_exists("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp")) { unlink("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.xmp"); }
	if (file_exists("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib")) { unlink("{$vars['path']['tmp']}/temp/{$key}/meta_{$key}.bib"); }
	if (file_exists("{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg")) { unlink("{$vars['path']['tmp']}/temp/{$key}/jpeg_{$key}.jpg"); }
	if (file_exists("{$vars['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg")) { unlink("{$vars['path']['tmp']}/2048/".substr($key,0,2)."/{$key}.jpg"); }
	if (file_exists("{$vars['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg")) { unlink("{$vars['path']['tmp']}/1024/".substr($key,0,2)."/{$key}.jpg"); }
	if (file_exists("{$vars['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg")) { unlink("{$vars['path']['tmp']}/180/".substr($key,0,2)."/{$key}.jpg"); }
	if (is_dir("{$vars['path']['tmp']}/temp/{$key}")) { rmdir("{$vars['path']['tmp']}/temp/{$key}"); }
	if (@mysql_query("DELETE FROM editor.image WHERE image_id='{$key}'")) { echo " > image place in '-failed-to-ingest' directory..."; }
}

?>