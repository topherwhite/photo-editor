<?php

$srvr = "localhost";
$user = "editor";
$pswd = "ky0yI3hqeIbB";


require_once("/liz/www/archive/scr/inc/mysql_sess.inc.php");

$tm = mktime();

$path['bambi'] = "aao-img/photoeditor";

$path['db'] = "editor_archive.";

$path['main_srvr'] = "/";
$path['img_srvr'] = "http://editor.againstallodds.com/archive/";

/*
$usr = "rs";

require_once("xml2array.inc.php"); $img_disp['file'] =  xml2array(file_get_contents("{$usr}.xml"),1);

$img_disp['size'] = $img_disp['file']['img']['size']['attr'];
$img_disp['qual'] = $img_disp['file']['img']['qual']['attr'];
$img_disp['tble'] = $img_disp['file']['img']['tble']['attr'];

for ($d=0;$d<count($img_disp['file']['img']['rate']['flat']);$d++)
{	$img_disp['rate']['flat'][$d] = $img_disp['file']['img']['rate']['flat'][$d]['attr']['clr'];
	$img_disp['rate']['hovr'][$d] = $img_disp['file']['img']['rate']['hovr'][$d]['attr']['clr'];	
}

for ($d=0;$d<count($img_disp['file']['img']['chck']['flat']);$d++)
{	$img_disp['chck']['flat'][$d] = $img_disp['file']['img']['chck']['flat'][$d]['attr']['clr'];
	$img_disp['chck']['hovr'][$d] = $img_disp['file']['img']['chck']['hovr'][$d]['attr']['clr'];
}
*/

$img_disp['size'] = array(	"thmb" => 150	, "view" => 700	, "orig" => 0, "work" => 480, "down" => 1024 );
$img_disp['qual'] = array(	"thmb" => 70	, "view" => 75	, "orig" => 75, "work" => 75, "down" => 60	);
$img_disp['tble'] = array(	"bttn_size" => 33,	"pad" => 10, "per_row" => 7, "num_rows" => 3	);
$img_disp['rate'] = array(
	"flat" => array( 0=>"222222", 1=>"aa5555", 3=>"aaaa55", 5=>"55aa55", 4=>"55aaaa", 2=>"5555aa" ),
	"hovr" => array( 0=>"444444", 1=>"aa1111", 3=>"aaaa11", 5=>"11aa11", 4=>"11aaaa", 2=>"1111aa" ));
$img_disp['chck'] = array(	
	"flat" => array( 0=>"000000", 1=>"00ff00" ),
	"hovr" => array( 0=>"000000", 1=>"ff0000" ));

/*
$CampMon['api_key'] = "7444b1f684690fb568d2f9f56e86f04271cd62a4";
$CampMon['client_id'] = 94814;

$CampMon['list_id']['OrderConfirm'] = 408203;
$CampMon['query_get']['OrderConfirm'] = "mail_notification_sent=1111111111";
$CampMon['custom']['OrderConfirm'][0] = "order_number";

$CampMon['list_id']['EmailCover'] = 413227;
$CampMon['custom']['EmailCover'][0] = "email_sender";
$CampMon['custom']['EmailCover'][1] = "name_sender";
$CampMon['custom']['EmailCover'][2] = "cover_id";

$CampMon['list_id']['SendCoupon'] = 421409;
$CampMon['custom']['SendCoupon'][0] = "name_sender";
$CampMon['custom']['SendCoupon'][1] = "coupon";
$CampMon['custom']['SendCoupon'][2] = "coupon_type";
$CampMon['custom']['SendCoupon'][3] = "coupon_subj";
*/


$req = array( 0 => "title" , 1 => "cptn" , 2 => "credit" , 3 => "src", 4 => "src_ref" , 5 => "loc_city"
			, 6 => "loc_state" , 7 => "loc_country"
			);
$ttl = array( "title"=>"Photo Title" , "cptn"=>"Photo Caption" , "credit"=>"Photographer Credit" , "src"=>"Source", "src_ref"=>"Source Reference" , "loc_city"=>"Location (City)"
			, "loc_state"=>"Location (State)" , "loc_country"=>"Location (Country)"
			);


$url_dir = substr($_SERVER["REQUEST_URI"],1);

?>