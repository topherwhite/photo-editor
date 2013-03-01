#!/usr/bin/php -q
<?php

require_once("/liz/editor/inc/php/vars.inc.php");

if ($con = mysql_conn($var))
{	
	$virtual_users = "";
	$users = mysql_query("SELECT * FROM editor.user",$con);
	for ($i = 0; $i < mysql_num_rows($users); $i++)
	{	$user = mysql_fetch_array($users);
		
		// create manual upload directories
		$dir = "{$var['path']['tmp']}/upld_web/{$user['email']}";
		if (!file_exists($dir))
		{	mkdir($dir);
			chown($dir,"apache");
			chgrp($dir,"apache");
			echo "\nmanual upload: directory created for '{$user['email']}'";
		}
		
		// set webdav
		$dir = "{$var['path']['tmp']}/upld_webdav/{$user['email']}";
		if (!file_exists($dir))
		{	mkdir($dir);
			chown($dir,"apache");
			chgrp($dir,"apache");
			echo "\nwebdav: directory created for '{$user['email']}'\n";
		}
		exec("htpasswd -b /liz/httpd/auth/webdav_users.dav {$user['email']} {$user['password']}");
		
		// set vsftpd
		$dir = "{$var['path']['tmp']}/upld_vsftpd/{$user['email']}";
		if (!file_exists($dir))
		{	mkdir($dir);
			chown($dir,"ftp");
			chgrp($dir,"ftp");
			echo "\nvsftpd: directory created for '{$user['email']}'";
		}
		$virtual_users .= "{$user['email']}\n{$user['password']}\n";
	}

	$tmp_file = "/liz/vsftpd/virtual-users.txt";
	if (file_exists($tmp_file)) { unlink($tmp_file); }
	$write_hndl = fopen($tmp_file, "w+");
	if ($write_hndl) { fwrite($write_hndl,$virtual_users); fclose($write_hndl); }
	exec("db_load -T -t hash -f {$tmp_file} /liz/vsftpd/virtual-users.db");
	if (file_exists($tmp_file)) { unlink($tmp_file); }
}


?>