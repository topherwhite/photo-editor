<?php 
if ((!empty($_SERVER['HTTP_ACCEPT_ENCODING'])) && (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'],"gzip")))
{ ob_start("ob_gzhandler"); } else { ob_start(); }

header('Content-type: text/javascript');

echo 	"\n".file_get_contents("browserdetect.js")
		."\n".file_get_contents("getflashversion.js")
		."\n".file_get_contents("ajax.js")
		."\n".file_get_contents("editor.js")
		;

?>