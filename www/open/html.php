<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<title>wint.erti.me</title>
</head> 
<body style="background-color:#ffffff; font-family:arial, verdana; font-size:12px;"> 
	<div style="position:relative; width:100%; height:1px; margin-left:auto; margin-right:auto; top:1%; overflow:visible; border:none; min-width:960px; width:expression(document.body.clientWidth < 960?"960px":"auto");"> 
		
		<table style="position:relative; width:420px; margin:0px auto 0px auto; top:50px; left:0px; border:solid 2px #aaaaaa; background-color:white; border-radius:6px; -moz-border-radius:6px;"> 
		
		<tr>
			<td style="width:20px; border:none;"></td>
			<td colspan="2" style="text-align:center;"><img src="https://wint.erti.me/img/stat/logo/wint.erti.me.png" /><img style="position:relative;top:4px;" src="https://wint.erti.me/img/dyn/txt?str=24_190_222222_ffffff_futura_wint.erti.me" /></td>
			<td style="width:20px; border:none;"></td>
		</tr> 
		
		<tr>
			<td colspan="4" style="font-size:9px; padding:5px 30px 5px 30px; border:none;"></td>
		</tr>
	
		<tr>
			<td style="width:20px; border:none;"></td>
			<td colspan="2" style="text-align:left;"><?php
			
			require_once("/liz/editor/inc/php/vars.inc.php");
			$url_params = explode("/",$url_dir);
			$text_lang = "en"; if (!empty($url_params[3])) { $text_lang = $url_params[3]; }
			$text_title = ""; if (!empty($url_params[2])) { $text_title = $url_params[2]; }
						
			if ($con = mysql_conn($var))
			{
				$qu = mysql_fetch_array(mysql_query("SELECT text_{$text_lang} AS txt FROM editor.text WHERE title='{$text_title}'",$con));
				echo str_replace("\n","<br />",$qu['txt']);
			}
						
			?></td>
			<td style="width:20px; border:none;"></td>
		</tr> 
			 
		<tr>
			<td colspan="4" style="font-size:9px; padding:5px 30px 20px 30px; border:none;"></td>
		</tr> 
			
		</table>
		
	</div> 
</body> 
</html>