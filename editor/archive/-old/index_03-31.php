<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Photo Editor - Against All Odds Productions, Inc.</title>
<link href="/scr/css/main.css" rel="stylesheet" type="text/css" />
<?php

require_once("scr/inc/var.inc.php");
require_once("scr/inc/popup.inc.php");


$connection = @mysql_connect($srvr,$user,$pswd);
$usr = "rs";

//session_start();
//if (!empty($_SESSION['usr'])) { $usr = mysqlclean($_SESSION,"usr",2,$connection); }
//else { header("Location: /login.php"); exit; }

$name = mysql_fetch_array(mysql_query("SELECT value_name AS fullname FROM {$path['db']}value_lists WHERE value_type='user' AND value='{$usr}'",$connection));

$info['opt']['x_y'] = ($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])-$img_disp['tble']['bttn_size']);


$style_slct = array( "grid"=>"", "list"=>"");
if	(	!empty($_GET['style'])
	&& 	(($_GET['style'] == "grid") || ($_GET['style'] == "list"))
	) { $stylesheet = $_GET['style']; }
else { $stylesheet = "grid"; }
$style_slct[$stylesheet] = " selected=\"selected\"";


echo "\n<link href=\"/scr/css/view_{$stylesheet}.css.php?key=".hash('md5',serialize($img_disp))."\" rel=\"stylesheet\" type=\"text/css\" />";
		
?>

<script type="text/javascript" src="/scr/java/photo_upload.js"></script>
<script type="text/javascript" src="/scr/java/ajax.js"></script>
<!--<script type="text/javascript" src="/scr/java/crop.js"></script>-->
<script type="text/javascript" src="/scr/java/ui.js"></script>
<script type="text/javascript" src="/scr/java/popup.js"></script>
<script type="text/javascript" src="/scr/java/cptn.js"></script>
</head>

<?php



if (!empty($_GET['view'])) { $view_nmbr = intval($_GET['view']); }
else { $view_nmbr = 1; }

$view_per_page = ($img_disp['tble']['num_rows']*$img_disp['tble']['per_row']);

$fltr['val'] = ""; $fltr['clear_bttn'] = "";
$fltr['slct'] = array( 'img*rating'=>'', 'meta*aao_src'=>'' ); 

if (!empty($_GET['fltr']))
{ 	$fltr = explode("*",$_GET['fltr']);
	$fltr['slct']["{$fltr[0]}*{$fltr[1]}"] = " selected=\"selected\"";
	$fltr['val'] = $fltr[2];
	if ($fltr[0] == 'img')
	{ 	$fltr['WHERE']['alb'] = " AND combo_tbl.{$fltr[1]}='{$fltr[2]}'";
	 	$fltr['WHERE']['all'] = " AND {$path['db']}{$fltr[0]}.{$fltr[1]}='{$fltr[2]}'";
	}
	if ($fltr[0] == 'meta')
	{ 	$fltr['WHERE']['alb'] = " AND LOWER(combo_tbl.{$fltr[1]})='".strtolower($fltr[2])."'";
	 	$fltr['WHERE']['all'] = " AND LOWER({$path['db']}{$fltr[0]}.{$fltr[1]})='".strtolower($fltr[2])."'";
	}
	
$fltr['clear_bttn'] = " or <input type=\"button\" class=\"apply\" value=\"clear\" onClick=\"fltr_view(0)\" />";
}
else { $fltr['WHERE']['alb'] = ""; $fltr['WHERE']['all'] = ""; }


if (empty($_GET['alb']) || ($_GET['alb'] == '-'))
{ 	$qu['CNTR'] = "";
	$qu['SELECT'] = "{$path['db']}img.*";
	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=0{$fltr['WHERE']['all']}";	
	$qu['ORDER'] = " {$path['db']}img.time DESC";
	
	$in_alb1 = mysql_query("SELECT ref_time FROM {$path['db']}albms WHERE ref_user='{$usr}' GROUP BY ref_time;",$connection);
	for ($i = 0; $i < mysql_num_rows($in_alb1); $i++)
	{ $in_alb2[$i] = mysql_fetch_array($in_alb1); $is_in_alb[$in_alb2[$i]['ref_time']] = 1; }
}
elseif ($_GET['alb'] == "xxxxxxxxxxxx")
{	$qu['CNTR'] = "";
	$qu['SELECT'] = "{$path['db']}img.*";
 	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=1{$fltr['WHERE']['all']}";	
	$qu['ORDER'] = 	"{$path['db']}img.time DESC";
}
else
{	$alb_ref = mysqlclean($_GET,"alb",12,$connection);
 	mysql_query("SET @cntr = 0",$connection);
	$qu['CNTR'] = ", (@cntr := @cntr+1) AS rnk";
	$qu['SELECT'] = "*";
//	$qu['FROM'] = 	"{$path['db']}img, {$path['db']}albms";
	$qu['FROM'] = 	"( SELECT * FROM {$path['db']}img, {$path['db']}albms"
					." WHERE {$path['db']}albms.album='{$alb_ref}'"
					." AND {$path['db']}img.time={$path['db']}albms.ref_time"
					." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
					." AND {$path['db']}img.deleted=0 ) AS combo_tbl";
	$qu['WHERE'] = 	"combo_tbl.album='{$alb_ref}'"
					." AND combo_tbl.time=combo_tbl.ref_time"
					." AND combo_tbl.creator=combo_tbl.ref_user"
					." AND combo_tbl.deleted=0"
					."{$fltr['WHERE']['alb']}";
	$qu['ORDER'] = 	"(rnk-display) ASC";
}

if (!empty($_GET['spec']))
{	$spec = explode(".",$_GET['spec']);
 	$qu['WHERE'] .= " AND {$path['db']}img.time=".intval($spec[1]);
}

$qu['final'] = "SELECT {$qu['SELECT']}{$qu['CNTR']} FROM {$qu['FROM']} WHERE {$qu['WHERE']} ORDER BY {$qu['ORDER']}";

//die($qu['final']);


echo 	"<body onLoad=\""
				."change_all('rate',0);"
				."change_all('slct',0);"
				."change_all('orig_hires',0);"
			."\">"
			
		."<input type=\"hidden\" id=\"url_val_alb\" value=\"{$_GET['alb']}\" />"
		."<input type=\"hidden\" id=\"url_val_view\" value=\"{$_GET['view']}\" />"	
		."<input type=\"hidden\" id=\"url_val_spec\" value=\"{$_GET['spec']}\" />"	
		."<input type=\"hidden\" id=\"url_val_fltr\" value=\"{$_GET['fltr']}\" />"
		."<input type=\"hidden\" id=\"url_val_style\" value=\"{$_GET['style']}\" />"
			
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
		
		."<label for=\"style_slct\">view style: </label>"
		."<select name=\"style_slct\" id=\"style_slct\" onChange=\"set_spec_img();change_view({$view_nmbr});\">"
		."<option value=\"grid\"{$style_slct['grid']}>grid</option>"
		."<option value=\"list\"{$style_slct['list']}>list</option>"
		."</select>"		
		
		."<br /><br /><label for=\"view_slct\">viewing: </label>"
		."<select name=\"view_slct\" id=\"view_slct\" onChange=\"change_view(1)\">"
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
		."<input type=\"text\" id=\"get_spec_val\" name=\"get_spec_val\" value=\"\" size=\"17\" maxlength=\"17\" />"
		."<input type=\"button\" class=\"apply\" value=\"->\""
			." onClick=\"set_all_photos();change_view(1);\" />"		

		."<br /><br /><label for=\"view_slct\">filter: </label>"
			."<select name=\"fltr_slct\" id=\"fltr_slct\" onChange=\"\">"
			."<option value=\"-\">- no filter -</option>"
			."<option value=\"img*rating\"{$fltr['slct']['img*rating']}>rating</option>"
	//		."<option value=\"meta*aao_src\"{$fltr['slct']['aao_src']}>agency</option>"
			."</select>"
		." = <input type=\"text\" id=\"fltr_slct_val\" name=\"fltr_slct_val\" value=\"{$fltr['val']}\" size=\"5\" />"
		."<input type=\"button\" class=\"apply\" value=\"->\""
			." onClick=\"fltr_view(1)\" />"
		. $fltr['clear_bttn']
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
{	echo "<br /><br />"
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



$imgs = mysql_query($qu['final'],$connection);

echo "\n<br />Welcome {$name['fullname']}"

	."\n<br /><br /><div class=\"photo_table\">"

		."\n<img class=\"load\" src=\"/img/static/bttns/mag_.png\" />"
		."<img class=\"load\" src=\"/img/static/bttns/info_.png\" />"
		;
$n = 0;	$r = 0; $slct_id = 0; $nmbr_imgs = mysql_num_rows($imgs);

if (($view_nmbr*$view_per_page) >= $nmbr_imgs) { $view_max = $nmbr_imgs; }
else { $view_max = ($view_nmbr*$view_per_page); }



echo "<div style=\"position:relative;top:-30px;left:50px;width:400px;font-size:14px;\">";

if ($view_nmbr > 1) { echo "<input type=\"button\" class=\"apply\" value=\"&lt;&lt;\""
								." onClick=\"change_view(".($view_nmbr-1).")\" />"; }

echo (($view_nmbr-1)*$view_per_page+1)."-{$view_max} of {$nmbr_imgs}";

if (($view_nmbr*$view_per_page) < $nmbr_imgs) { echo "<input type=\"button\" class=\"apply\" value=\"&gt;&gt;\""
								." onClick=\"change_view(".($view_nmbr+1).")\" />"; }

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
	
//	$info['cell']['x'] = ($n*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
//	$info['cell']['y'] = ($r*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
	$info['img']['x'] = (round(($img_disp['size']['thmb']-$info['img']['w'])/2)+$img_disp['tble']['pad']);
	$info['img']['y'] = (round(($img_disp['size']['thmb']-$info['img']['h'])/2)+$img_disp['tble']['pad']);
	
	$info['cell']['rating'] = array(0=>"",1=>"",2=>"",3=>"",4=>"",5=>"");
	$info['cell']['rating'][$img['rating']] = " selected=\"selected\"";
	
	if (!empty($is_in_alb[$img['time']])) { $is_in_alb['str'] = "visible"; } else { $is_in_alb['str'] = "hidden"; }
	if ($img['hires_added'] != 1111111111) { $have_hires['str'] = "visible"; $have_hires['bttn'] = ""; }
	else
	{	$have_hires['str'] = "hidden";
	 	$have_hires['bttn'] = "<input class=\"apply\" type=\"button\" value=\"upload hi-res version\" onClick=\"\" />"
						//	." or <input class=\"apply\" type=\"button\" value=\"upload hi-res version\" onClick=\"\" />"
							;
	}
	$orig_is_hires["{$img['hires_added']}"] = "";			$dflt_orig_is_hires["{$img['hires_added']}"] = "false";
	$orig_is_hires['1234567890'] = " checked=\"checked\"";	$dflt_orig_is_hires['1234567890'] = "true";
	
	$n++;
	
echo	""
		
		.popup_open($slct_id,"view",($view_per_page-$slct_id))		
			."<img id=\"popup_img_view_{$slct_id}\" src=\"/img/static/gears.gif\" />"
					."<br /><br /><a class=\"download\" target=\"download\""
					." href=\"{$path['img_srvr']}img/dynamic/down.{$img['creator']}.{$img['time']}.jpg\">"
				."download cropped full-res</a> ({$img['crop_wd']} x {$img['crop_ht']})"
		.popup_close()
		
		.popup_open($slct_id,"meta",($view_per_page-$slct_id))	
			."<br /><img src=\"{$path['img_srvr']}img/dynamic/thmb.{$img['creator']}.{$img['time']}.jpg\" />"
			."<br /><br />"
			."<input class=\"apply\" type=\"button\" value=\"edit metadata on separate screen...\" onClick=\"location='/metadata.php?wh={$img['time']}';\" />"
			."<br /><br />"
			."<input class=\"apply\" type=\"button\" value=\"move photo to trash\" onClick=\"confirm_delete(1,{$slct_id})\" />"	
			."<br /><br />"
			."<input class=\"apply\" type=\"button\" value=\"move left\" onClick=\"req_chng_ordr('lf',{$slct_id})\" />"
			."<input class=\"apply\" type=\"button\" value=\"move right\" onClick=\"req_chng_ordr('rt',{$slct_id})\" />"
			."<br /><br />"
			."<label for=\"orig_hires_{$slct_id}\">orig. upload is hi-res: </label>"
			."<input type=\"checkbox\" name=\"orig_hires_{$slct_id}\" id=\"orig_hires_{$slct_id}\" value=\"1\""
					." onClick=\"req_orig_is_hires({$slct_id})\""
					.$orig_is_hires["{$img['hires_added']}"]." />"	
			."<br /><br />"
			." or <input class=\"apply\" type=\"button\" value=\"upload hi-res version\" onClick=\"open_win('hi_res',{$slct_id})\" />"
		.popup_close()
		
		."\n<div class=\"thmb_cell_{$slct_id}\" id=\"cell_{$slct_id}\""
			." style=\""
				."background-color:#{$img_disp['rate']['flat'][$img['rating']]};\""
			." onMouseOver=\"thbhvr(1,{$slct_id})\""
			." onMouseOut=\"thbhvr(2,{$slct_id})\""
			.">"

		."<div class=\"in_album\" id=\"in_alb_{$slct_id}\" style=\"visibility:{$is_in_alb['str']};\"></div>"
		."<div class=\"have_hires\" id=\"have_hires_{$slct_id}\" style=\"visibility:{$have_hires['str']};\"></div>"		

		."<img class=\"thmb_img\" id=\"thmb_{$slct_id}\""
				." style=\"top:{$info['img']['y']}px;left:{$info['img']['x']}px;"
					."width:{$info['img']['w']}px;height:{$info['img']['h']}px;\""
				." onClick=\"slct_checkbox(4,{$slct_id})\""
				." src=\"{$path['img_srvr']}img/dynamic/thmb.{$img['creator']}.{$img['time']}.jpg\" />"
		
		
		."<div class=\"opt1\" id=\"opt1_{$slct_id}\">"
	//		."<a class=\"download\" target=\"download\" href=\"/img/dynamic/down.{$img['creator']}.{$img['time']}.jpg\">"
			."<img src=\"/img/static/bttns/mag.png\" id=\"opt1_img_{$slct_id}\""
			." onClick=\"popup(1,{$slct_id},'view')\""
		//	." onClick=\"confirm_delete(1,{$slct_id})\""
			." onMouseOver=\"imghvr(1,'opt1_img_{$slct_id}')\""
			." onMouseOut=\"imghvr(2,'opt1_img_{$slct_id}')\""
			." />"
	//		."</a>"
		."</div>"
		
		."<div class=\"opt2\" id=\"opt2_{$slct_id}\">"
			."<img src=\"/img/static/bttns/info.png\" id=\"opt2_img_{$slct_id}\""
		//		." onClick=\"location='/metadata.php?wh={$img['time']}';\""
				." onClick=\"popup(1,{$slct_id},'meta')\""
			." onMouseOver=\"imghvr(1,'opt2_img_{$slct_id}')\""
			." onMouseOut=\"imghvr(2,'opt2_img_{$slct_id}')\""
			."/>"
		."</div>"
		
		."<div class=\"opt3\" id=\"opt3_{$slct_id}\">"
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
		
		."<div class=\"opt4\" id=\"opt4_{$slct_id}\">"
			."<input type=\"checkbox\" id=\"slct_{$slct_id}\" value=\"{$img['creator']}_{$img['time']}\"" 
				." onClick=\"slct_checkbox(3,{$slct_id})\" />"
		."</div>"		
		
		."<input type=\"hidden\" id=\"val_usr_{$slct_id}\" value=\"{$img['creator']}\" />"
		."<input type=\"hidden\" id=\"val_wh_{$slct_id}\" value=\"{$img['time']}\" />"
		."<input type=\"hidden\" id=\"dflt_rate_{$slct_id}\" value=\"{$img['rating']}\" />"
		."<input type=\"hidden\" id=\"dflt_orig_hires_{$slct_id}\" value=\"".$dflt_orig_is_hires["{$img['hires_added']}"]."\" />"

		."</div>"	
		;

//$orig_note[$slct_id] = "";
//$orig_cptn[$slct_id] = "";
	
if ($stylesheet == "list")
{
	$vers_hist = ""; $latest_cptn = "";
	
	$cptn_vers_qu = mysql_query("SELECT * FROM {$path['db']}cptn WHERE usr='{$img['creator']}' AND time={$img['time']} ORDER BY updated ASC",$connection);
	
	for ($j = 0; $j < mysql_num_rows($cptn_vers_qu); $j++)	
	{	$cptn_vers = mysql_fetch_array($cptn_vers_qu);
		if ($cptn_vers['versioned'] == 1)
		{ $vers_hist .= "<option value=\"{$cptn_vers['updated']}\">".date("D, j M, G:i",$cptn_vers['updated'])." - {$cptn_vers['version_tag']}</option>"; }
		if ($cptn_vers['versioned'] == 0)
		{ $latest_cptn .= $cptn_vers['caption']; }
	}
	
	echo "\n<div class=\"meta_cell_{$slct_id}\" id=\"meta_{$slct_id}\">"

			
		."<select class=\"vers_cptn\" name=\"vers_cptn_{$slct_id}\" id=\"vers_cptn_{$slct_id}\""
				." onChange=\"req_cptn_show_vers({$slct_id})\">"	
				."<option value=\"1111111111\" selected=\"selected\">latest</option>"
				.$vers_hist
				."</select>"
		."<label for=\"vers_cptn_{$slct_id}\" class=\"vers_cptn\">version history: </label>"
		."<br /><br />"
		."<input class=\"apply\" style=\"float:right;\" type=\"button\" value=\"->\" onClick=\"req_cptn_vers({$slct_id})\" />"
		."<input type=\"text\" name=\"save_vers_tag_{$slct_id}\" id=\"save_vers_tag_{$slct_id}\" size=\"10\" maxlength=\"10\" value=\"\" style=\"float:right;\" />"
		."<label for=\"save_vers_tag_{$slct_id}\" style=\"float:right;\">save a version: </label>"
		."<br /><br />"
		."<input class=\"apply\" style=\"float:right;\" type=\"button\" value=\"view original meta\" onClick=\"popup(1,{$slct_id},'meta_show')\" />"
		."<br /><br />"
		."<input class=\"apply\" style=\"float:right;\" type=\"button\" value=\"write/edit notes\" onClick=\"popup(1,{$slct_id},'notes')\" />"
		
		."<textarea class=\"text_cptn\" id=\"text_cptn_{$slct_id}\">{$latest_cptn}</textarea>"
		."</div>";
		
$meta_qu = mysql_fetch_array(mysql_query("SELECT * FROM {$path['db']}meta WHERE creator='{$img['creator']}' AND time={$img['time']} LIMIT 1",$connection));

//$orig_note[$slct_id] = str_replace("'","\\'",$meta_qu['aao_notes']);
//$orig_cptn[$slct_id] = str_replace("'","\\'",$latest_cptn);

echo 	""
		.popup_open($slct_id,"meta_show",($view_per_page-$slct_id))
		."<b>Original Meta</b>"
		."<br /><b>Title:</b> {$meta_qu['aao_title']}"
		."<br /><b>Caption:</b> {$meta_qu['aao_cptn']}"
		."<br /><b>Photo Source:</b> {$meta_qu['aao_src']}"
		."<br /><b>Source ID:</b> {$meta_qu['aao_src_ref']}"		
		."<br /><b>Photo Credit:</b> {$meta_qu['aao_credit']}"		
		."<br /><b>Location:</b> {$meta_qu['aao_loc_city']}, {$meta_qu['aao_loc_state']}"
		."<br /><b>Page Spread:</b> {$meta_qu['aao_loc_country']}"
		.popup_close()
		
		
 		.popup_open($slct_id,"notes",($view_per_page-$slct_id))
		."<textarea id=\"text_note_{$slct_id}\">{$meta_qu['aao_notes']}</textarea>"
		.popup_close()
		;		

}
		
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
echo 	" var val_text_cptn = new Array(); var val_text_note = new Array();";
		for ($i = 0; $i < $slct_id; $i++) { echo " val_text_cptn[{$i}] = ''; val_text_note[{$i}] = '';"; }
echo 	" var nmbr_visible = {$slct_id};"
		." var path_img_srvr = '{$path['img_srvr']}';"
 		." </script>";

if ($stylesheet == "list") { echo "<img class=\"load\" src=\"/img/static/trans.gif\" onLoad=\"check_changed()\" />"; }
	

?>
</body>
</html>