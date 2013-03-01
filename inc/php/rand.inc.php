<?php

function generate_key($lngth,$exclude_numbers=null)
{	$key = '';
	if (empty($exclude_numbers)) { for ($i=0; $i<=9; $i++) { $range[$i] = $i; } $subtract = 87; } else { $subtract = 97; }
	for ($i=97; $i<=122; $i++) { $range[($i-$subtract)] = chr($i); }
	for ($i=0; $i<=3; $i++) { shuffle($range); }
	for ($i = 0; $i < mt_rand((2*$lngth),(3*$lngth)); $i++) { $key .= $range[mt_rand(0,(count($range)-1))]; }
	return substr($key,mt_rand(0,($lngth/2-1)),$lngth);
}


function generate_unique_key($con,$table,$column,$key_length,$exclude_numbers=null)
{
	$key_test['cnt'] = 1;
	for ($i = 0; $key_test['cnt'] > 0; $i++)
	{	$key = strtolower(generate_key($key_length,$exclude_numbers));
		$key_test = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cnt FROM {$table} WHERE {$column}='{$key}'",$con));
	}
	return $key;
}

?>