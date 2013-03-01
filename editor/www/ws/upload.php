<?php

require_once("/liz/editor/inc/php/vars.inc.php");
session_start();
if (empty($_SESSION['user_id'])) { die("not logged in..."); }
else { $user = mysql_fetch_array(mysql_query("SELECT email FROM editor.user WHERE user_id='{$_SESSION['user_id']}'",$con)); }

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">" 
		."\n<html xmlns=\"http://www.w3.org/1999/xhtml\">"
		."<head><meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\"></head><body>";

$rep = 0; if (!empty($_POST['upl_rep'])) { $rep = intval($_POST['upl_rep']); }

if (!empty($user['email']) && !empty($_FILES["upl_file_{$rep}"]["tmp_name"]))
{	if (move_uploaded_file(	$_FILES["upl_file_{$rep}"]["tmp_name"]
							,"{$var['path']['tmp']}/upld_web/{$user['email']}/".$_FILES["upl_file_{$rep}"]["name"]
	))
	{	echo	"<input type=\"hidden\" id=\"file_error_{$rep}\" value=\"none\" />"
				."<input type=\"hidden\" id=\"file_name_{$rep}\" value=\"".$_FILES["upl_file_{$rep}"]["name"]."\" />"
				."<input type=\"hidden\" id=\"file_size_{$rep}\" value=\"".intval($_FILES["upl_file_{$rep}"]["size"])."\" />"
				."<input type=\"hidden\" id=\"file_type_{$rep}\" value=\"".$_FILES["upl_file_{$rep}"]["type"]."\" />"
				;
	}
	else { echo	"<input type=\"hidden\" id=\"file_error_{$rep}\" value=\"failed\" />"; }
}

echo "</body></html>";

?>