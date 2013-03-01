<?php

$input_vars = $_REQUEST;

$cookie_expire_time = 17280000;

$key_length = 5;

$var['microtime'] = microtime(true);
$var['tm'] = floor($var['microtime']);

$var['id']['project'] = "abcde";
$var['id']['origin'] = "abcde";

$var['ws']['action'] = array("rate","bucket_create","bucket_delete","bucket_image_add","bucket_image_remove","bucket_image_rank","position_scale","rename");
$var['ws']['ws'] = array("search","update","list","meta");
$var['ws']['list'] = array("bucket","layout","log","origin","project","user");
$var['ws']['return_limit'] = 256;
$var['ws']['value'] = array(	'rate'=>'/^[0-5]$/'
								,'bucket_create'=>'/.*/'
								,'rename'=>'/.*/'
								,'bucket_image_add'=>'/^[a-z][a-z][a-z][a-z][a-z]$/'
								,'bucket_image_remove'=>'/^[a-z][a-z][a-z][a-z][a-z]$/'
								,'bucket_image_rank'=>'/^([0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9])$/'
								,'position_scale'=>'/^'.'(0|-[1-9]|-[1-9][0-9]|-[1-9][0-9][0-9]|-[1-9][0-9][0-9][0-9]|-[1-3][0-2][0-7][0-6][0-7]|[1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-3][0-2][0-7][0-6][0-7])'
														.',(0|-[1-9]|-[1-9][0-9]|-[1-9][0-9][0-9]|-[1-9][0-9][0-9][0-9]|-[1-3][0-2][0-7][0-6][0-7]|[1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-3][0-2][0-7][0-6][0-7])'
														.',(0|-[1-9]|-[1-9][0-9]|-[1-9][0-9][0-9]|-[1-9][0-9][0-9][0-9]|-[1-3][0-2][0-7][0-6][0-7]|[1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-3][0-2][0-7][0-6][0-7])'
														.',([1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-3][0-2][0-7][0-6][0-7])'
														.',([1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-3][0-2][0-7][0-6][0-7])'
														.',(0|-[1-9]|-[1-9][0-9]|-[1-3][0-5][0-9]|[1-9]|[1-9][0-9]|[1-3][0-5][0-9])'
														.'$/'
							);

$var['img']['4096']['sq'] = 4096;
$var['img']['2048']['sq'] = 2048;
$var['img']['1024']['sq'] = 1024;
$var['img']['180']['sq'] = 180;

$read_write_env = "s3";
$var['creds']['s3'] = array("AKIAI5ND7YMXBZTJD2RQ","D1esSSfOV+EY9H4Y4U2c23/ekvc8jVRT0gJpQy0W");
//$var['path']['tmp'] = "../../data";
$var['path']['tmp'] = "/mnt/editor-data";


$var['creds']['mysql']['srvr'] = "localhost";
$var['creds']['mysql']['user'] = "editor";
$var['creds']['mysql']['pswd'] = "ky0yI3hqeIbB";
$var['creds']['mysql']['db'] = "editor";

$var['creds']['cm']['api_key'] = "cff3622c991202e2adfd7df3a82de109";
$var['creds']['cm']['client_id'] = "3aa363385bb2e2908ccb472987ce774a";

if (!empty($_SERVER["REQUEST_URI"])) { $url_dir = substr($_SERVER["REQUEST_URI"],1); $url_arr = explode("/",$url_dir); }

$var['html']['ios']['style'] = "<style type=\"text/css\"> table.meta { width:100%; border:solid 1px green; overflow:hidden; font-size:10px; } td.ttl { font-size:200%; } td.lf { width:40%; border-bottom:dotted 1px gray; border-right:dotted 1px gray; font-weight:bold; vertical-align:top; } td.rt { width:60%; border-bottom:dotted 1px gray; vertical-align:top; } </style>";
$var['html']['ios']['start'] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\"><head><meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; maximum-scale=1.0;\">{$var['html']['ios']['style']}<title>aaoeditor</title></head><body style=\"font-family:arial;\">";
$var['html']['ios']['end'] = "</body></html>";


function mysqlclean($string,$maxlength,$con)
{	if (!empty($string) && ($maxlength != 0)) { return mysql_real_escape_string(substr($string,0,$maxlength),$con); }
	elseif (!empty($string) && ($maxlength == 0)) { return mysql_real_escape_string($string,$con); }
	else { return null; }
}

function mysql_conn($var_arr)
{	return @mysql_connect($var_arr['creds']['mysql']['srvr'],$var_arr['creds']['mysql']['user'],$var_arr['creds']['mysql']['pswd']);
}


//require_once("../../inc/php/mysql_sess.inc.php");

?>