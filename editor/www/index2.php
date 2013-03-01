<?php

require_once("/liz/editor/inc/php/vars.inc.php");
session_start();
if (empty($_SESSION['user_id'])) { header("Location: /login"); exit; }

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<title>wint.erti.me</title> 
<link href="/css/univ.css" rel="stylesheet" type="text/css" />
<script src="/js/all.js" type="text/javascript"></script>
</head>
<body onLoad=""> 

		<div id="container">
		<table id="bodytable">
		<tr><td colspan="3" id="topbar">asdf</td></tr>
		<tr>
		
		<td id="leftbar">
		w
		</td>
		
		<td id="midbody">
		<?php
		
		$img_qu = mysql_query("SELECT * FROM editor.image where ingestion_user='{$_SESSION['user_id']}'",$con);
		
		for ($i = 0; $i < mysql_num_rows($img_qu); $i++)
		{
			$img_data = mysql_fetch_array($img_qu);
			
			echo "<div class=\"img_thmb\" style=\"width:150px;height:150px;overflow:hidden;position:relative;float:left;\">"
				."<img class=\"img_thmb\" style=\"width:100%;\" src=\"/ws/img/{$img_data['image_id']}_180\" />"
				."</div>";
			echo "<br />";
		}
		
		?>
		</td>
		
		<td id="rightbar">
		w
		</td>
		</tr>
		</table>
		</div>
</body> 
</html>