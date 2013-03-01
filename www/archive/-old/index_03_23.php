<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Photo Editor - Against All Odds Productions, Inc.</title>
<link href="/scr/css/editor.css" rel="stylesheet" type="text/css" />
<?php

require_once("scr/inc/var.inc.php");


$connection = @mysql_connect($srvr,$user,$pswd);
$usr = "rs";

//session_start();
//if (!empty($_SESSION['usr'])) { $usr = mysqlclean($_SESSION,"usr",2,$connection); }
//else { header("Location: /login.php"); exit; }

$name = mysql_fetch_array(mysql_query("SELECT value_name AS fullname FROM {$path['db']}value_lists WHERE value_type='user' AND value='{$usr}'",$connection));

$info['opt']['x_y'] = ($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])-$img_disp['tble']['bttn_size']);

echo "\n<style type=\"text/css\">"
		." div.photo_table div.thmb_cell {"
			." width:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))."px;"
			." height:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))."px;"
			." }"
		." div.opt1, div.opt2, div.opt3, div.opt4 {"
			." width:{$img_disp['tble']['bttn_size']}px;"
			." height:{$img_disp['tble']['bttn_size']}px;"
			." }"	
		." div.opt2 { left:{$info['opt']['x_y']}px; }"	
		." div.opt3 { top:{$info['opt']['x_y']}px;left:{$info['opt']['x_y']}px; }"
		." div.opt4 { top:{$info['opt']['x_y']}px; }"	
		." div.opt4 input {"
			." margin-top:".round($img_disp['tble']['bttn_size']/3)."px;"
			." margin-right:".round($img_disp['tble']['bttn_size']/3)."px;"
			." }"
		
		." div.in_album { left:".(($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))/2-13)."px;"
						." top:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])-26-2)."px; }"
		
		." div.popup { min-width:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+20)."px;"
					." min-height:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+20)."px; }"
		
		."</style>";
		



?>

<script type="text/javascript" src="/scr/java/photo_upload.js"></script>
<script type="text/javascript" src="/scr/java/ajax.js"></script>
<!--<script type="text/javascript" src="/scr/java/crop.js"></script>-->
<script type="text/javascript" src="/scr/java/ui.js"></script>
<script type="text/javascript" src="/scr/java/popup.js"></script>
</head>

<?php


echo 	"<body onLoad=\""
				."change_all('rate',0);"
				."change_all('slct',0);"
			."\">"
			
		."<input type=\"hidden\" id=\"url_val_alb\" value=\"{$_GET['alb']}\" />"
		."<input type=\"hidden\" id=\"url_val_view\" value=\"{$_GET['view']}\" />"	
		."<input type=\"hidden\" id=\"url_val_spec\" value=\"{$_GET['spec']}\" />"	
			
		."<div>"

		."<div class=\"topmatter\">"
	
		."\n<div class=\"photo_upload\">"
		."\n\t<form id=\"photo_upload\""
			." action=\"/scr/php/sub_upload?key={$tm}\""
			." enctype=\"multipart/form-data\" method=\"post\""
			." style=\"\""
		.">"
		
		."\n\t<input id=\"photo_upload_file\" name=\"photo_upload_file\" type=\"file\""
		//	." onChange=\"check_upl_type();\""
		." />"
			
		."\n\t<br /><br /><input type=\"button\" value=\"upload image file\""
			." onClick=\"photo_upload_submit();\""
			." style=\"cursor:pointer;\""
			." />"
		."\n\t</form>"
		."\n</div>"
				
		."\n<div class=\"album_control\">"
		
		."<label for=\"view_slct\">viewing: </label>"
		."<select name=\"view_slct\" id=\"view_slct\" onChange=\"view_album();\">"
		."<option value=\"-\">- show all photos -</option>"
		;




$query['get_albs'] = "SELECT * FROM {$path['db']}value_lists WHERE value_type='album' ORDER BY created DESC";
$albs = mysql_query($query['get_albs'],$connection);
for ($o = 0; $o < mysql_num_rows($albs); $o++)
{
	$albs_val = mysql_fetch_array($albs);
	if (!empty($_GET['alb']) && ($albs_val['value'] == $_GET['alb']))
	{ $alb_sel = " selected=\"selected\""; } else { $alb_sel = ""; }
	echo "<option value=\"{$albs_val['value']}\"{$alb_sel}>{$albs_val['value_name']}</option>";
}

if ($_GET['alb'] == 'xxxxxxxxxxxx') { $alb_sel_trash = " selected=\"selected\""; } else { $alb_sel_trash = ""; }

echo 	"<option value=\"xxxxxxxxxxxx\"{$alb_sel_trash}>- trash bin -</option>"

 		."</select>"
		
		."<input type=\"hidden\" id=\"val_albm_curr\" value=\"{$_GET['alb']}\" />"

		."<br /><br /><label for=\"get_spec_val\">show spec. photo: </label>"
		."<input type=\"text\" id=\"get_spec_val\" name=\"get_spec_val\" value=\"{$_GET['spec']}\" size=\"17\" maxlength=\"17\" />"
		."<input type=\"button\" class=\"apply\" value=\"->\""
			." onClick=\"show_spec_img()\" />"		

//		."<br /><br /><label for=\"view_slct\">filter view: </label>"
//			."<select name=\"fltr_slct\" id=\"fltr_slct\" onChange=\"\">"
//			."<option value=\"-\">- choose a value -</option>"
//			."</select>"

		."\n</div>";



echo 	"\n<div class=\"view_select\">";


if (!empty($_GET['alb']) && ($_GET['alb'] != '-'))			
{	
echo 	"<input type=\"button\" class=\"apply\" value=\"download full album\" style=\"float:right;\" onClick=\"download_full_album('{$_GET['alb']}');\" />";
}


echo 	"<input type=\"button\" class=\"apply\" value=\"select all\" onClick=\"change_all('slct',1)\" />"
		."<input type=\"button\" class=\"apply\" value=\"de-select all\" onClick=\"change_all('slct',0)\" />"
		
		
		



	."<br /><br />"

 		."<label for=\"albm_slct\">add selected photos to: </label>"
			."<select id=\"albm_slct\" name=\"albm_slct\""
				." onChange=\"document.getElementById('val_albm_slct').value"
					."=document.getElementById('albm_slct').options"
						."[document.getElementById('albm_slct').selectedIndex].value\">"
			."<option value=\"-\">- choose an album -</option>";

$query['get_albs'] = "SELECT * FROM {$path['db']}value_lists WHERE value_type='album' ORDER BY created DESC";
$albs = mysql_query($query['get_albs'],$connection);
for ($o = 0; $o < mysql_num_rows($albs); $o++)
{
	$albs_val = mysql_fetch_array($albs);
	echo "<option value=\"{$albs_val['value']}\">{$albs_val['value_name']}</option>";
}

echo 	"</select>"

		."<input type=\"hidden\" id=\"val_albm_slct\" value=\"xxxxxxxxxxxx\" />"

//		."<br /><br /><input type=\"text\" value=\"\" id=\"create_album_name\" class=\"create_album_name\" />"
//		."<input type=\"button\" value=\"add bucket\" onClick=\"req_create_album()\" />"

		."<input type=\"button\" class=\"apply\" value=\"->\""
			." onClick=\"gather_checked('alb_add','{$usr}')\" />";

if (!empty($_GET['alb']) && ($_GET['alb'] != '-'))			
{	echo "<br />"
		."<input type=\"button\" class=\"apply\" value=\"remove selected from current album\""
			." onClick=\"gather_checked('alb_rem','{$_GET['alb']}')\" />"
			;
			
}			


		
echo 	"<br /><br /><label for=\"batch_rating_slct\">apply rating to selected photos: </label>"
		."<select id=\"batch_rating_slct\" name=\"batch_rating_slct\""
		." onChange=\"document.getElementById('batch_img_rating').value"
				."=document.getElementById('batch_rating_slct').options"
					."[document.getElementById('batch_rating_slct').selectedIndex].value\">"
			."<option value=\"0\">-</option><option value=\"1\">1</option><option value=\"2\">2</option>"
			."<option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option>"
			."</select>"
		."<input type=\"hidden\" id=\"batch_img_rating\" value=\"0\" />"
		."<input type=\"button\" class=\"apply\" value=\"->\""
			." onClick=\"gather_checked('batch_apply','{$usr}','img_rating')\" />"





		."\n</div>"
		
		
		."</div>"
		;

if (!empty($_GET['view'])) { $view_nmbr = intval($_GET['view']); }
else { $view_nmbr = 1; }

$view_per_page = ($img_disp['tble']['num_rows']*$img_disp['tble']['per_row']);



if (empty($_GET['alb']) || ($_GET['alb'] == '-'))
{ 	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=0";	
	$qu['ORDER'] = " {$path['db']}img.time DESC";
	
	$in_alb1 = mysql_query("SELECT ref_time FROM {$path['db']}albms WHERE ref_user='{$usr}' GROUP BY ref_time;",$connection);
	for ($i = 0; $i < mysql_num_rows($in_alb1); $i++)
	{ $in_alb2[$i] = mysql_fetch_array($in_alb1); $is_in_alb[$in_alb2[$i]['ref_time']] = 1; }
}
elseif ($_GET['alb'] == "xxxxxxxxxxxx")
{	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=1";	
	$qu['ORDER'] = 	"{$path['db']}img.time DESC";
}
else
{	$alb_ref = mysqlclean($_GET,"alb",12,$connection);

	$qu['FROM'] = 	"{$path['db']}img, {$path['db']}albms";
	$qu['WHERE'] = 	"{$path['db']}albms.album='{$alb_ref}'"
					." AND {$path['db']}img.time={$path['db']}albms.ref_time"
					." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
					." AND {$path['db']}img.deleted=0";
	$qu['ORDER'] = 	"{$path['db']}img.time DESC";
}

if (!empty($_GET['spec']))
{	$spec = explode(".",$_GET['spec']);
 	$qu['WHERE'] .= " AND {$path['db']}img.time=".intval($spec[1]);
}

$qu['final'] = "SELECT {$path['db']}img.* FROM {$qu['FROM']} WHERE {$qu['WHERE']} ORDER BY {$qu['ORDER']}";

//die($qu['final']);

$imgs = mysql_query($qu['final'],$connection);

echo "\n<br />Welcome {$name['fullname']}"

	."\n<br /><br /><div class=\"photo_table\">"

		."\n<img class=\"load\" src=\"/img/static/bttns/mag_.png\" />"
		."<img class=\"load\" src=\"/img/static/bttns/info_.png\" />"
		;
$n = 0;	$r = 0; $slct_id = 0; $nmbr_imgs = mysql_num_rows($imgs);

if (($view_nmbr*$view_per_page) >= $nmbr_imgs) { $view_max = $nmbr_imgs; }
else { $view_max = ($view_nmbr*$view_per_page); }



echo "<div style=\"position:relative;top:-30px;left:200px;width:400px;font-size:14px;\">";

if ($view_nmbr > 1) { echo "<input type=\"button\" class=\"apply\" value=\"&lt;&lt;\""
								." onClick=\"location='?view=".($view_nmbr-1)."&alb={$_GET['alb']}';\" />"; }

echo (($view_nmbr-1)*$view_per_page+1)."-{$view_max} of {$nmbr_imgs}";

if (($view_nmbr*$view_per_page) < $nmbr_imgs) { echo "<input type=\"button\" class=\"apply\" value=\"&gt;&gt;\""
								." onClick=\"location='?view=".($view_nmbr+1)."&alb={$_GET['alb']}';\" />"; }

echo "</div>";

for ($i = 0; $i < $nmbr_imgs; $i++)	
{
	$img = mysql_fetch_array($imgs);
	
if (	($i >= (($view_nmbr-1)*$view_per_page) )
	&&	($i < ($view_nmbr*$view_per_page) )
	)
{	
	if ($img['crop_wd'] >= $img['crop_ht'])
	{ 	$info['img']['w'] = $img_disp['size']['thmb'];
		$info['img']['h'] = round(($img['crop_ht']/$img['crop_wd'])*$img_disp['size']['thmb']);
	}
	else
	{ 	$info['img']['h'] = $img_disp['size']['thmb'];
		$info['img']['w'] = round(($img['crop_wd']/$img['crop_ht'])*$img_disp['size']['thmb']);
	}
	
	$info['cell']['x'] = ($n*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
	$info['cell']['y'] = ($r*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
	$info['img']['x'] = (round(($img_disp['size']['thmb']-$info['img']['w'])/2)+$img_disp['tble']['pad']);
	$info['img']['y'] = (round(($img_disp['size']['thmb']-$info['img']['h'])/2)+$img_disp['tble']['pad']);
	
	$info['cell']['rating'] = array(0=>"",1=>"",2=>"",3=>"",4=>"",5=>"");
	$info['cell']['rating'][$img['rating']] = " selected=\"selected\"";
	
	if (!empty($is_in_alb[$img['time']])) { $is_in_alb['str'] = "visible"; } else { $is_in_alb['str'] = "hidden"; }
	
	$n++;
	
echo	""
		
		."<div class=\"popup\" id=\"popup_view_{$slct_id}\" style=\"visibility:hidden;"
					."left:".($info['cell']['x']-20)."px;"
					."top:".($info['cell']['y']-20)."px;"
					."z-index:".($view_per_page-$slct_id).";\">"
			."<div class=\"popup_x\" id=\"popup_view_{$slct_id}_x\""
				." onMouseOver=\"\""
				." onMouseOut=\"\""
				." onClick=\"popup(0,{$slct_id},'view');\""
				."></div>"
			."<a class=\"download\" target=\"download\""
					." href=\"{$path['img_srvr']}img/dynamic/down.{$img['creator']}.{$img['time']}.jpg\">"
				."download cropped full-res</a> ({$img['crop_wd']} x {$img['crop_ht']})"
			."<br /><br /><img id=\"popup_img_view_{$slct_id}\" src=\"/img/static/gears.gif\" />"
		."</div>"
		
		."<div class=\"popup\" id=\"popup_meta_{$slct_id}\" style=\"visibility:hidden;"
					."left:".($info['cell']['x']-20)."px;"
					."top:".($info['cell']['y']-20)."px;"
					."z-index:".($view_per_page-$slct_id).";\">"
			."<div class=\"popup_x\" id=\"popup_meta_{$slct_id}_x\""
				." onMouseOver=\"\""
				." onMouseOut=\"\""
				." onClick=\"popup(0,{$slct_id},'meta');\""
				."></div>"
			."<br /><img src=\"{$path['img_srvr']}img/dynamic/thmb.{$img['creator']}.{$img['time']}.jpg\" />"
			."<br /><br />"
			."<input class=\"apply\" type=\"button\" value=\"edit metadata on separate screen...\" onClick=\"location='/metadata.php?wh={$img['time']}';\" />"
			."<br /><br />"
			."<input class=\"apply\" type=\"button\" value=\"move photo to trash\" onClick=\"confirm_delete(1,{$slct_id})\" />"			
		."</div>"			
		
		
		
		."\n<div class=\"thmb_cell\" id=\"cell_{$slct_id}\""
			." style=\"left:{$info['cell']['x']}px;top:-20px;"
				."top:{$info['cell']['y']}px;"
			//	."z-index:".($view_per_page-$slct_id).";"
				."background-color:#{$img_disp['rate']['flat'][$img['rating']]};\""
			." onMouseOver=\"thbhvr(1,{$slct_id})\""
			." onMouseOut=\"thbhvr(2,{$slct_id})\""
			.">"

		."<div class=\"in_album\" id=\"in_alb_{$slct_id}\" style=\"visibility:{$is_in_alb['str']};\"></div>"

		."\n\t<img class=\"thmb_img\" id=\"thmb_{$slct_id}\""
				." style=\"top:{$info['img']['y']}px;left:{$info['img']['x']}px;"
					."width:{$info['img']['w']}px;height:{$info['img']['h']}px;\""
				." onClick=\"slct_checkbox(4,{$slct_id})\""
				." src=\"{$path['img_srvr']}img/dynamic/thmb.{$img['creator']}.{$img['time']}.jpg\" />"
		
		
		."\n\t<div class=\"opt1\" id=\"opt1_{$slct_id}\">"
	//		."<a class=\"download\" target=\"download\" href=\"/img/dynamic/down.{$img['creator']}.{$img['time']}.jpg\">"
			."<img src=\"/img/static/bttns/mag.png\" id=\"opt1_img_{$slct_id}\""
			." onClick=\"popup(1,{$slct_id},'view')\""
		//	." onClick=\"confirm_delete(1,{$slct_id})\""
			." onMouseOver=\"imghvr(1,'opt1_img_{$slct_id}')\""
			." onMouseOut=\"imghvr(2,'opt1_img_{$slct_id}')\""
			." />"
	//		."</a>"
		."</div>"
		
		."\n\t<div class=\"opt2\" id=\"opt2_{$slct_id}\">"
			."<img src=\"/img/static/bttns/info.png\" id=\"opt2_img_{$slct_id}\""
		//		." onClick=\"location='/metadata.php?wh={$img['time']}';\""
				." onClick=\"popup(1,{$slct_id},'meta')\""
			." onMouseOver=\"imghvr(1,'opt2_img_{$slct_id}')\""
			." onMouseOut=\"imghvr(2,'opt2_img_{$slct_id}')\""
			."/>"
		."</div>"
		
		."\n\t<div class=\"opt3\" id=\"opt3_{$slct_id}\">"
			."<select id=\"rate_{$slct_id}\""
				." onChange=\"req_rate({$slct_id})\">"
			."<option value=\"0\"{$info['cell']['rating'][0]}></option>"
			."<option value=\"1\"{$info['cell']['rating'][1]}>1</option>"
			."<option value=\"2\"{$info['cell']['rating'][2]}>2</option>"
			."<option value=\"3\"{$info['cell']['rating'][3]}>3</option>"
			."<option value=\"4\"{$info['cell']['rating'][4]}>4</option>"
			."<option value=\"5\"{$info['cell']['rating'][5]}>5</option>"
			."</select>"
		."</div>"
		
		."\n\t<div class=\"opt4\" id=\"opt4_{$slct_id}\">"
			."<input type=\"checkbox\" id=\"slct_{$slct_id}\" value=\"{$img['creator']}_{$img['time']}\"" 
				." onClick=\"slct_checkbox(3,{$slct_id})\" />"
		."</div>"		
		
		."<input type=\"hidden\" id=\"val_usr_{$slct_id}\" value=\"{$img['creator']}\" />"
		."<input type=\"hidden\" id=\"val_wh_{$slct_id}\" value=\"{$img['time']}\" />"
		."<input type=\"hidden\" id=\"dflt_rate_{$slct_id}\" value=\"{$img['rating']}\" />"

		."\n</div>"	
		;
		
	if ($n == $img_disp['tble']['per_row']) { $r++; $n = 0; } $slct_id++;
}
}
				
		
echo 	"\n</div>";


?>

</div>

<?php

echo 	"\n<script type=\"text/javascript\">"
		." var thmb_bulge_magnitude = ".floor($img_disp['tble']['pad']/4).";"
		." var chck_clr_flat = new Array();"
			." chck_clr_flat[0] = '{$img_disp['chck']['flat'][0]}'; chck_clr_flat[1] = '{$img_disp['chck']['flat'][1]}';"
//		." var chck_clr_hovr = new Array();"
//			." chck_clr_hovr[0] = '{$img_disp['chck']['hovr'][0]}'; chck_clr_hovr[1] = '{$img_disp['chck']['hovr'][1]}';"
		." var rate_clr_flat = new Array(); var rate_clr_hovr = new Array();";
		for ($i = 0; $i < count($img_disp['rate']['flat']); $i++)
		{ echo 	" rate_clr_flat[{$i}] = '{$img_disp['rate']['flat'][$i]}';"
				." rate_clr_hovr[{$i}] = '{$img_disp['rate']['hovr'][$i]}';"; }
echo 	" var nmbr_visible = {$slct_id};"
		." var path_img_srvr = '{$path['img_srvr']}';"
 		." </script>";

?>
</body>
</html>