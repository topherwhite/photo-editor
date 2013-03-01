<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Photo Editor - Against All Odds Productions, Inc.</title>
<link href="/scr/css/editor.css" rel="stylesheet" type="text/css" />
<style type="text/css">
div.drk { position:absolute; background-color:transparent; border:none; z-index:1; background-image:url('/img/static/drk.png'); }
div.edit_tool { position:absolute; background-color:transparent; }
</style>
<?php

require_once("scr/inc/var.inc.php");

?>
<script type="text/javascript" src="/scr/java/crop.js"></script>
<script type="text/javascript" src="/scr/java/ajax.js"></script>
<script type="text/javascript" src="/scr/java/ui.js"></script>
<script type="text/javascript">
var metaset = new Array();
<?php for ($i = 0; $i < count($req); $i++) { echo "metaset['{$req[$i]}'] = 0;\n"; } ?>
</script>
</head>
<body onMouseUp="div_action_end();">
<div class="container">
	

<?php



//session_start();
//$usr = mysqlclean($_SESSION,"usr",2,$connection);


$connection = @mysql_connect($srvr,$user,$pswd);

$usr = "rs";

$wh = mysqlclean($_GET,"wh",10,$connection);

$query['get_img'] = "SELECT * FROM {$path['db']}img WHERE creator='{$usr}' AND time={$wh}";
$query['get_meta'] = "SELECT * FROM {$path['db']}meta WHERE creator='{$usr}' AND time={$wh}";

$img = mysql_fetch_array(mysql_query($query['get_img'],$connection));


$meta = mysql_fetch_array(mysql_query($query['get_meta'],$connection));
$meta['orig'] = unserialize($meta['orig_meta']);

$img['src'] = "/img/dynamic.php/work.{$usr}.{$wh}.jpg";

if ($img['orig_wd'] >= $img['orig_ht'])
{ 	$conv = $img_disp['size']['work']/$img['orig_wd'];
	$upl_photo_wd = 480;
	$upl_photo_ht = $conv*$img['orig_ht'];
}
else
{ 	$conv = $img_disp['size']['work']/$img['orig_ht'];
	$upl_photo_ht = 480;
	$upl_photo_wd = $conv*$img['orig_wd'];
}



$cover_initial_wd = $img['crop_wd']*$conv;
$cover_initial_ht = $img['crop_ht']*$conv;
$cover_left_pos = $img['crop_x']*$conv;
$cover_top_pos = $img['crop_y']*$conv;

echo "\n<script type=\"text/javascript\">"
	." var bound_top = ". (0) .";"
	." var bound_left = ". (0) .";"
	." var bound_right = ". ($upl_photo_wd) .";"
	." var bound_bottom = ". ($upl_photo_ht) .";"
	." var edit_wd = {$upl_photo_wd};"
	." var mouse_status = 'u';"
	." var check_changed = '';"
	." var global_xpos = 0;"
	." var global_ypos = 0;"
	." var global_wd = {$cover_initial_wd};"
	." var global_ht = {$cover_initial_ht};"
	." var top_pos = 0;"
	." var left_pos = 0;"
	." var x_shift = 0;"
	." var y_shift = 0;"
	." var minimum_wd = 150;"
	." var update_freq = 50;"
	." var editor_usr = '{$usr}';"
	." var editor_wh = '{$wh}';"
	." var conversion = {$conv};"

//	." function end_action() { div_action_end('{$wh}',{$tm}); }"

	." </script>";




echo "\n<div class=\"meta_photodisp\""
		." style=\"width:{$upl_photo_wd}px;height:{$upl_photo_ht}px;background-image:url({$img['src']});\">"
//	."<img class=\"meta_photodisp\""
//		." style=\"width:{$img['orig_wd']}px;height:{$img['orig_ht']}px;\""
//		." src=\"{$img['src']}\" />"
		
		
		
				."\n<div id=\"temp_div\""
					." class=\"edit_tool\" style=\""
					."z-index:3;border:dashed 1px orange;"
					."left:". ($cover_left_pos-1) ."px;"
					."top:". ($cover_top_pos-1) ."px;"
					."width:". ($cover_initial_wd) ."px;"
					."height:". ($cover_initial_ht) ."px;"
					."\""
					." onMouseOver=\"\""
			//		." onMouseOut=\"document.body.style.cursor='default';\""
					.">"

					."\n<div id=\"temp_div_bttn\""
						." class=\"edit_tool\" style=\""
						."z-index:4;border:none;background-color:transparent;left:0px;top:0px;width:100%;height:100%;\""
				//		." onMouseDown=\"div_action_start('move');\""
				//		." onMouseDown=\"alert('move');\""
				//		." onMouseOver=\"document.body.style.cursor='move';\""
				//		." onMouseOut=\"document.body.style.cursor='default';\""
						."></div>"




						."\n<div id=\"temp_div_res_w\" class=\"edit_tool\" style=\""
									."z-index:5;"
									."cursor:e-resize;"
					//				."background-color:green;"
									."left:". ($cover_initial_wd - 7) ."px;"
									."top:". (0 - 7) ."px;"
									."width:". (14) ."px;"
									."height:". ($cover_initial_ht + 12) ."px;"
									."\""
								." onMouseDown=\"div_action_start('res_w');\""
								." onMouseOver=\"document.getElementById('temp_div_res_w').style.border='1px solid green';\""
								." onMouseOut=\"document.getElementById('temp_div_res_w').style.border='none';\""
								."></div>"

						."\n<div id=\"temp_div_res_h\" class=\"edit_tool\" style=\""
									."z-index:5;"
									."cursor:s-resize;"
					//				."background-color:green;"
									."left:".(0-7)."px;"
									."top:".($cover_initial_ht-7)."px;"
									."width:".($cover_initial_wd+12)."px;"
									."height:".(14)."px;"
									."\""
								." onMouseDown=\"div_action_start('res_h');\""	
								." onMouseOver=\"document.getElementById('temp_div_res_h').style.border='1px solid green';\""
								." onMouseOut=\"document.getElementById('temp_div_res_h').style.border='none';\""
								."></div>"
						
						."\n<div id=\"temp_div_res_top\" class=\"edit_tool\" style=\""
									."z-index:5;"
									."cursor:n-resize;"
					//				."background-color:blue;"
									."left:".(0-7)."px;"
									."top:".(0-7)."px;"
									."width:".($cover_initial_wd+12)."px;"
									."height:".(14)."px;"
									."\""
								." onMouseDown=\"div_action_start('res_top');\""
								." onMouseOver=\"document.getElementById('temp_div_res_top').style.border='1px solid green';\""
								." onMouseOut=\"document.getElementById('temp_div_res_top').style.border='none';\""
								."></div>"
									
						."\n<div id=\"temp_div_res_lft\" class=\"edit_tool\" style=\""
									."z-index:5;"
									."cursor:w-resize;"
					//				."background-color:blue;"
									."left:". (0 - 7) ."px;"
									."top:". (0 - 7) ."px;"
									."width:". (14) ."px;"
									."height:". ($cover_initial_ht + 12) ."px;"
									."\""
								." onMouseDown=\"div_action_start('res_lft');\""
								." onMouseOver=\"document.getElementById('temp_div_res_lft').style.border='1px solid green';\""
								." onMouseOut=\"document.getElementById('temp_div_res_lft').style.border='none';\""
								."></div>"			

				."\n</div>"		
		
		
		
					."\n<div class=\"drk\" id=\"drk_l\" style=\""
							."left:". (0) ."px;"
							."top:". (0) ."px;"
							."width:". ($cover_left_pos-1) ."px;"
							."height:". ($upl_photo_ht) ."px;"
							."\""
							." onMouseOver=\"document.body.style.cursor='default';\""
							."></div>"

						."\n<div class=\"drk\" id=\"drk_r\" style=\""
							."left:". ($cover_left_pos + $cover_initial_wd+1) ."px;"
							."top:". (0) ."px;"
							."width:". ($upl_photo_wd-$cover_left_pos-$cover_initial_wd-1) ."px;"
							."height:". ($upl_photo_ht) ."px;"
							."\""
							." onMouseOver=\"document.body.style.cursor='default';\""
							."></div>"

						."\n<div class=\"drk\" id=\"drk_t\" style=\""
							."left:". ($cover_left_pos-1) ."px;"
							."top:". (0) ."px;"
							."width:". ($cover_initial_wd + 2) ."px;"
							."height:". ($cover_top_pos) ."px;"
							."\""
							." onMouseOver=\"document.body.style.cursor='default';\""
							."></div>"

						."\n<div class=\"drk\" id=\"drk_b\" style=\""
							."left:". ($cover_left_pos-1) ."px;"
							."top:". ($cover_top_pos + $cover_initial_ht) ."px;"
							."width:". ($cover_initial_wd + 2) ."px;"
							."height:". ($upl_photo_ht - $cover_top_pos - $cover_initial_ht) ."px;"
							."\""
							." onMouseOver=\"document.body.style.cursor='default';\""
							."></div>"
		
		
		
	."</div>"
	."<br />"
	."<div class=\"meta_metadisp\">";


echo "\n<form id=\"photo_metadata\" name=\"photo_metadata\""
		." method=\"post\" enctype=\"multipart/form-data\""
		." action=\"/scr/php/sub_meta.php?wh={$wh}&usr={$usr}\">"
	."<table class=\"meta_enter\">"
	."<tr><td class=\"meta_sctn_ttl\" colspan=\"2\">All the requested fields below must be filled in order to enter the photo into the system.</td></tr>"
	;

		
for ($i = 0; $i < count($req); $i++)
{
	echo "<tr><td class=\"meta_ttl\">{$ttl[$req[$i]]}:</td><td class=\"meta_textarea\"><input type=\"text\" id=\"wr_{$req[$i]}\" name=\"aao_{$req[$i]}\" value=\"".$meta["aao_{$req[$i]}"]."\" /></td></tr>";
	
}
	
echo "<tr><td colspan=\"2\">"
		."<input type=\"button\" value=\"submit image to system\""
			." onClick=\"photo_metadata_submit();\""
			." style=\"float:right;margin-top:10px;\""
			." />"
	."</td></tr>"
	."</table>"
	."</form>";

	
echo "<table class=\"meta_enter\">"
	."<tr><td class=\"meta_sctn_ttl\" colspan=\"2\">The following information was found, and may be used to fill the fields above.</td></tr>"
	;

foreach ($meta['orig'] as &$value)
{	
	$ind = key($meta['orig']);
	$val = current($meta['orig']);
	if ( 	(!empty($val))
		&& 	($ind != "Image Width")		&& 		($ind != "Image Height")	&& 		($ind != "File Name")
		&& 	($ind != "Directory")		&& 		($ind != "File Size")		&& 		($ind != "File Type")
		&& 	($ind != "Exif Byte Order")	&& 		($ind != "MIME Type")		&& 		($ind != "Bits Per Sample")
		&& 	($ind != "Compression")		&& 		($ind != "Samples Per Pixel")	&& 	($ind != "Photometric Interpretation")
		&& 	($ind != "Strip Offsets")	&& 		($ind != "Rows Per Strip")	&& 		($ind != "Strip Byte Counts")
		&& 	($ind != "Profile Copyright")	&& 	($ind != "Profile Description")	&&	($ind != "Make And Model")
		&& 	($ind != "Image Size")		&& 		($ind != "Profile Description ML")&&($ind != "Video Card Gamma")
		&& 	($ind != "Red Tone Reproduction Curve")		&& 		($ind != "Green Tone Reproduction Curve")
		&& 	($ind != "Blue Tone Reproduction Curve")		&& 		($ind != "Chromatic Adaptation")
		&& 	($ind != "Native Display Info")	&&	($ind != "Media White Point")	&&	($ind != "Blue Matrix Column")
		&& 	($ind != "Profile Version")	&&	($ind != "Red Matrix Column")	&&	($ind != "Green Matrix Column")
		&& 	($ind != "Extra Samples")	&&	($ind != "Sample Format")	&&	($ind != "Profile CMM Type")
		&& 	($ind != "Profile Version")	&&	($ind != "Red Matrix Column")	&&	($ind != "Green Matrix Column")
		&& 	($ind != "Primary Platform")	&&	($ind != "Profile ID")	&&	($ind != "Profile Creator")
		&& 	($ind != "Device Manufacturer")	&&	($ind != "Profile File Signature")	&&	($ind != "Rendering Intent")
		&& 	($ind != "Connection Space Illuminant")	&&	($ind != "Device Attributes")	&&	($ind != "Device Model")
		&& 	($ind != "Profile Class")	&&	($ind != "Predictor")	&&	($ind != "Planar Configuration")
		&& 	($ind != "Color Space Data")	&&	($ind != "CMM Flags")	&&	($ind != "Profile Connection Space")
		&& 	($ind != "File Modification Date/Time")	&&	($ind != "Orientation")	&&	($ind != "Profile Date Time")
		
		&& 	($ind != "Encoding Process")	&&	($ind != "Color Components")	&&	($ind != "Exif Image Height")
		&& 	($ind != "Y Resolution")	&&	($ind != "Resolution Unit")	&&	($ind != "Exif Image Width")
		&& 	($ind != "X Resolution")	&&	($ind != "Y Cb Cr Sub Sampling")	&&	($ind != "Media Black Point")
		
		&& 	($ind != "Supplemental Categories")	&&	($ind != "Date/Time Created")	&&	($ind != "Date/Time Original")
		&& 	($ind != "By-line Title")	&&	($ind != "Date Created")	&&	($ind != "Category")
		&& 	($ind != "JFIF Version")	&&	($ind != "Coded Character Set")	&&	($ind != "Application Record Version")
		&& 	($ind != "Writer-Editor")	&&	($ind != "Time Created")	&&	($ind != "Urgency")
		
		)
	{
		echo 	"<tr><td class=\"meta_ttl\">"
				."<select id=\"sl_{$ind}\" onChange=\"fill_meta('{$ind}')\">"
				."<option value=\"-\">---</option>";
				
		for ($i = 0; $i < count($req); $i++)
		{	echo "<option value=\"{$req[$i]}\">".strtolower($ttl[$req[$i]])."</option>";	
		}				
		echo	"</select> :</td>"
				."<td class=\"meta_disptext\" id=\"pre_val_{$ind}\">".substr($val,2)."</td></tr>";
	}
}
	
		echo 	"<tr><td class=\"meta_ttl\">"
				."<select id=\"sl_filename\" onChange=\"fill_meta('filename')\">"
				."<option value=\"-\">---</option>";
				
		for ($i = 0; $i < count($req); $i++)
		{	echo "<option value=\"{$req[$i]}\">".strtolower($ttl[$req[$i]])."</option>";	
		}				
		echo	"</select> :</td>"
				."<td class=\"meta_disptext\" id=\"pre_val_filename\">{$meta['orig_filename']}</td></tr>";	

echo "</table>";


	
echo "</div>";






?>




<?php


/*
echo "<form id=\"metadata\" action=\"/scr/php/sub_upload?key={$tm}\" enctype=\"multipart/form-data\" method=\"post\" style=\"\">"
			. "<input id=\"upl_file\" name=\"upl_file\" type=\"file\" size=\"20\""
		//	." onChange=\"check_upl_type();\""
			." style=\"width:80%px;left:10%;border:none;\" />"
			
			."<input type=\"submit\" value=\"Submit\" />"
			. "\n</form>"

			
;
*/		


?>
		
</div>
</body>
</html>