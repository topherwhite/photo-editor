<?php

function GenerateKey($lngth)
{	$key = '';
	for ($i=0; $i<=9; $i++) { $range[$i] = $i; }
	for ($i=97; $i<=122; $i++) { $range[($i-87)] = chr($i); }
	for ($i=0; $i<=3; $i++) { shuffle($range); }
	for ($i = 0; $i < mt_rand((2*$lngth),(3*$lngth)); $i++) { $key .= $range[mt_rand(0, count($range))]; }
	return substr($key,mt_rand(0,($lngth/2-1)),$lngth);
}


?>