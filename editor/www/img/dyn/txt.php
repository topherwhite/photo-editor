<?php
header('Content-Type: image/png');

require_once "../../../inc/php/imgtxt.inc.php";

$str = explode("_",$_GET['str']);
$txt['siz'] = $str[0];
$txt['wd'] = $str[1];
$txt['ht'] = 2*$str[0];
$txt['clr'] = $str[2];
$txt['bgc'] = $str[3];
$txt['fnt'] = strval(strtolower($str[4]));
$txt['msg'] = str_replace("^","#",str_replace("*"," ",$str[5]));

$txt['x'] = 10;
$txt['y'] = round($txt['ht']*0.67);
$quality = 1;

/*
$txt = array(	'siz' => 12
				,'wd' => 250
				,'ht' => 24
				,'x' => 10
				,'y' => 7
				,'clr' => 'ffffff'
				,'bgc' => 'fdbb30'
				,'fnt' => 'frutiger75'
				,'msg' => 'featured article'
			);
*/

$mkImg = new putTxtOnImg();

$mkImg->Message($txt['msg']);

$mkImg->Angle(0);

$mkImg->Font("../../../inc/fonts/{$txt['fnt']}");

$mkImg->FontSize($txt['siz']);

$mkImg->Coordinate($txt['x'],$txt['y']);

$mkImg->Colors($txt['clr']);

$mkImg->WriteTXT($txt['ht'],$txt['wd'],$txt['bgc'],$quality);


?>
