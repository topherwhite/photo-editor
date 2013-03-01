<?php

require_once("scr/inc/xml2array.inc.php");

$obj = xml2array( file_get_contents("mime.xml") , $get_attributes=1);

foreach ($obj['types']['type'] as $ind=>$val)
{
	$exts = explode(" ",$val['attr']['ext']);
	foreach ($exts as $val2)
	{
		echo "\n<type ext=\"{$val2}\" mime=\"{$val['attr']['mime']}\" />";
	}
}

?>
