#!/usr/bin/php -q
<?php

require_once("../../inc/php/vars.inc.php");


$files = scandir("{$var['path']['tmp']}/done");
foreach($files as $ind=>$val) {	if (substr($val,0,1) != ".")
{	echo "\n{$var['path']['tmp']}/done/{$val}";
	if (rename("{$var['path']['tmp']}/done/{$val}","{$var['path']['tmp']}/upld/{$val}")) { echo " -> moved"; }		
}	}

$files = scandir("{$var['path']['tmp']}/orig");
foreach($files as $ind=>$val) {	if (substr($val,0,1) != ".")
{	echo "\n{$var['path']['tmp']}/orig/{$val}";
	if (unlink("{$var['path']['tmp']}/orig/{$val}")) { echo " -> deleted"; }		
}	}

$files = scandir("{$var['path']['tmp']}/jpeg");
foreach($files as $ind=>$val) {	if (substr($val,0,1) != ".")
{	echo "\n{$var['path']['tmp']}/jpeg/{$val}";
	if (unlink("{$var['path']['tmp']}/jpeg/{$val}")) { echo " -> deleted"; }		
}	}

$files = scandir("{$var['path']['tmp']}/work");
foreach($files as $ind=>$val) {	if (substr($val,0,1) != ".")
{	echo "\n{$var['path']['tmp']}/work/{$val}";
	if (unlink("{$var['path']['tmp']}/work/{$val}")) { echo " -> deleted"; }		
}	}

$files = scandir("{$var['path']['tmp']}/thmb");
foreach($files as $ind=>$val) {	if (substr($val,0,1) != ".")
{	echo "\n{$var['path']['tmp']}/thmb/{$val}";
	if (unlink("{$var['path']['tmp']}/thmb/{$val}")) { echo " -> deleted"; }		
}	}

if ($con = mysql_conn($var))
{	if (mysql_query("TRUNCATE editor.image",$con))
	{	echo "\nimage table purged...";
	}
} mysql_close($con);

echo "\n";

?>
