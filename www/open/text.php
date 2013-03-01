<?php
	require_once("/liz/editor/inc/php/vars.inc.php");
	$url_params = explode("/",$url_dir);
	$text_lang = "en"; if (!empty($url_params[3])) { $text_lang = $url_params[3]; }
	$text_title = ""; if (!empty($url_params[2])) { $text_title = $url_params[2]; }
			
	if ($con = mysql_conn($var))
	{
		$qu = mysql_fetch_array(mysql_query("SELECT text_{$text_lang} AS txt FROM editor.text WHERE title='{$text_title}'",$con));
		echo str_replace("\n","<br />",$qu['txt']);
	}
?>