<?php

require_once("../../inc/php/vars.inc.php");

if ($con = mysql_conn($var))
{	
	$error = "none";
	$sess_id = "none";
	$user_id = "none";
	
	header('Content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
			."\n<editor>";
	
	if (empty($input_vars['action']) || ($input_vars['action'] == "login"))
	{	if (empty($input_vars['login_pageload']) || ($var['tm']-300 > $input_vars['login_pageload'])) { $error = "timeout"; }
		else
		{	if (empty($input_vars['login_email']) || empty($input_vars['login_password'])) { $error = "nocreds"; }
			else	
			{	$email = mysqlclean(strtolower($input_vars['login_email']),100,$con);
				$pswd = mysqlclean($input_vars['login_password'],100,$con);
				$login_query = mysql_query("SELECT * FROM editor.user WHERE email='{$email}'",$con);
		
				if (mysql_num_rows($login_query) == 0) { $error = "bademail"; }
				else
				{	$login_row = mysql_fetch_array($login_query);
					if ($pswd != $login_row['password']) { $error = "badpass"; }
					else
					{
						session_start();
						$sess_id = session_id();
						if (!empty($login_row['user_id'])) { $user_id = $login_row['user_id']; }
						mysql_query("UPDATE editor.user SET last_login='".mktime()."' WHERE user_id='{$user_id}'",$con);
						$_SESSION['user_id'] = $login_row['user_id'];
						$_SESSION['project_id'] = $login_row['project_id'];
					}
				}
			}
		}
		echo "\n<login error=\"{$error}\" session_id=\"{$sess_id}\" user_id=\"{$login_row['user_id']}\" project_id=\"{$login_row['project_id']}\" session_method=\"cookie\" />";
	}
	elseif ($input_vars['action'] == "logout")
	{	
		session_start();
		
		if (!empty($_SESSION['user_id'])) { $user_id = $_SESSION['user_id']; }
		else { $error = "badid"; }
		
		session_destroy();
		
		echo "\n<logout error=\"{$error}\" user_id=\"{$user_id}\" />";
	}
	
	echo "\n</editor>";
	
}


?>