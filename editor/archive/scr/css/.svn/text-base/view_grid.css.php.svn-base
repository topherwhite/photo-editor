<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
header("Content-type: text/css");
require_once("../inc/var.inc.php");

echo "@charset \"utf-8\";";

$info['opt']['x_y'] = ($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])-$img_disp['tble']['bttn_size']);

echo ""
	."\n div.photo_table { position:relative; margin:40px 0px 0px 20px; width:100px; height:100px; z-index:1; }"
	."\n div.photo_table img.thmb_img { position:absolute; border:none; z-index:1; cursor:pointer; }"
	."\n div.opt1, div.opt2, div.opt3, div.opt4 { position:absolute; top:0px; left:0px; z-index:2; border:none; visibility:hidden; background-color:transparent; }"
	."\n div.opt3, div.opt4 { visibility:visible; }"
	."\n div.opt4 input { cursor:pointer; }"
	."\n div.opt1 img, div.opt2 img, div.opt3 img { cursor:pointer; position:absolute; top:0px; left:0px; width:100%; height:100%; background-color:transparent; border:none; }"
	."\n div.opt3 select { position:relative; left:-12px; top:7px; }"
	
	."\n div.in_album, div.have_hires, div.status_advanced { position:absolute; width:26px; height:26px; border:none; background-image:url(/img/static/slct.png); z-index:2; }"
	
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
		." div.have_hires { top:".(($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))/2-13)."px;"
						." left:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])-26-2)."px;"
						." }"			
		." div.status_advanced { top:".(($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))/2-13)."px;"
						." left:0px;"
						." }"				
		

	;
	
$n = 0;	$r = 0;
	
for ($i = 0; $i < ($img_disp['tble']['per_row']*$img_disp['tble']['num_rows']); $i++)
{
	
	
	$info['cell']['x'] = ($n*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
	$info['cell']['y'] = ($r*($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+5));
	
	$n++;
	
	echo ""
		."\ndiv.popup_view_{$i}, div.popup_meta_{$i} {"
					." position:absolute; background-color:#cccccc; height:auto; width:auto; color:black;"
	 				." border:solid 2px #f78f1e; visibility:visible; z-index:10; padding:20px 20px 20px 20px;"
					." min-width:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+20)."px;"
					." min-height:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad'])+20)."px;"
					." left:".($info['cell']['x']-20)."px;"
					." top:".($info['cell']['y']-20)."px;"
					." }"
		
		."\ndiv.thmb_cell_{$i} {"
					." background-color:#222222; position:absolute; z-index:0; border:solid 1px #000000;"
					." width:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))."px;"
					." height:".($img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))."px;"
					." left:".($info['cell']['x'])."px;"
					." top:".($info['cell']['y'])."px;"
					." }"
					
		."\ndiv.meta_cell_{$i} {"
					." background-color:transparent; position:absolute; z-index:0; border:none; visibility:hidden; overflow:hidden;"
					." width:0px;height:0px;"
					." left:".($info['cell']['x']+$img_disp['size']['thmb']+(2*$img_disp['tble']['pad']))."px;"
					." top:".($info['cell']['y'])."px;"
					." }"								
		;
	
	if ($n == $img_disp['tble']['per_row']) { $r++; $n = 0; }
}




?>