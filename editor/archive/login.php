<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Photo Editor - Against All Odds Productions, Inc.</title>
<link href="/css/editor.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
<script type="text/javascript" src="/scr/java/login.js"></script>
</head>
<body>
<div>
<?php

require_once("scr/inc/var.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$users = mysql_query("SELECT * FROM photoeditor.value_lists WHERE value_type='user' ORDER BY value",$connection);

	echo "<select id=\"user_login\" onChange=\"req_user_login()\" style=\"margin:100px;padding:5px;\">"
		."<option value=\"--\">--select a user--</option>";
	
	for ($i = 0; $i < mysql_num_rows($users); $i++)
	{	
		$usr[$i] = mysql_fetch_array($users);
		echo "<option value=\"{$usr[$i]['value']}\">{$usr[$i]['value_name']}</option>";
	}
	
	echo "</select>";

}
?>
</div>
</body>
</html>