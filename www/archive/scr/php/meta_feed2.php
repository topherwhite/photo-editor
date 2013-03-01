<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<editor>";

require_once("../../scr/inc/var.inc.php");
require_once("../../scr/inc/xml_clean.inc.php");

if ($connection = @mysql_connect($srvr,$user,$pswd))
{
	$alb = mysqlclean($_GET,"alb",12,$connection);




if (!empty($_GET['view'])) { $view_nmbr = intval($_GET['view']); }
else { $view_nmbr = 1; }

$view_per_page = ($img_disp['tble']['num_rows']*$img_disp['tble']['per_row']);

$fltr['val'] = ""; $fltr['clear_bttn'] = "";
$fltr['slct'] = array( 'img*rating'=>'', 'meta*aao_src'=>'', 'img*status'=>'' ); 

if (!empty($_GET['fltr']))
{ 	$fltr = explode("*",$_GET['fltr']);
	$fltr['slct']["{$fltr[0]}*{$fltr[1]}"] = " selected=\"selected\"";
	$fltr['val'] = $fltr[2];
	if ($fltr[0] == 'img')
	{ 	$fltr['WHERE']['alb'] = " AND combo_tbl.{$fltr[1]}='{$fltr[2]}'";
	 	$fltr['WHERE']['all'] = " AND {$path['db']}{$fltr[0]}.{$fltr[1]}='{$fltr[2]}'";
	}
	if ($fltr[0] == 'meta')
	{ 	$fltr['WHERE']['alb'] = " AND LOWER(combo_tbl.{$fltr[1]})='".strtolower($fltr[2])."'";
	 	$fltr['WHERE']['all'] = " AND LOWER({$path['db']}{$fltr[0]}.{$fltr[1]})='".strtolower($fltr[2])."'";
	}
	
$fltr['clear_bttn'] = " or <input type=\"button\" class=\"apply\" value=\"clear\" onClick=\"fltr_view(0)\" />";
}
else { $fltr['WHERE']['alb'] = ""; $fltr['WHERE']['all'] = ""; }


if (empty($_GET['alb']) || ($_GET['alb'] == '-'))
{ 	$qu['CNTR'] = "";
	$qu['SELECT'] = "{$path['db']}img.*";
	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=0{$fltr['WHERE']['all']}";	
	$qu['ORDER'] = " {$path['db']}img.time DESC";
	
	$in_alb1 = mysql_query("SELECT ref_time FROM {$path['db']}albms WHERE ref_user='{$usr}' GROUP BY ref_time;",$connection);
	for ($i = 0; $i < mysql_num_rows($in_alb1); $i++)
	{ $in_alb2[$i] = mysql_fetch_array($in_alb1); $is_in_alb[$in_alb2[$i]['ref_time']] = 1; }
}
elseif ($_GET['alb'] == "xxxxxxxxxxxx")
{	$qu['CNTR'] = "";
	$qu['SELECT'] = "{$path['db']}img.*";
 	$qu['FROM'] = 	"{$path['db']}img";
 	$qu['WHERE'] = 	"{$path['db']}img.creator='{$usr}'"
					." AND {$path['db']}img.deleted=1{$fltr['WHERE']['all']}";	
	$qu['ORDER'] = 	"{$path['db']}img.time DESC";
}
else
{	$alb_ref = mysqlclean($_GET,"alb",12,$connection);
 	mysql_query("SET @cntr = 0",$connection);
	$qu['CNTR'] = ", (@cntr := @cntr+1) AS rnk";
	$qu['SELECT'] = "*";
//	$qu['FROM'] = 	"{$path['db']}img, {$path['db']}albms";
	$qu['FROM'] = 	"( SELECT * FROM {$path['db']}img, {$path['db']}albms"
					." WHERE {$path['db']}albms.album='{$alb_ref}'"
					." AND {$path['db']}img.time={$path['db']}albms.ref_time"
					." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
					." AND {$path['db']}img.deleted=0 ) AS combo_tbl";
	$qu['WHERE'] = 	"combo_tbl.album='{$alb_ref}'"
					." AND combo_tbl.time=combo_tbl.ref_time"
					." AND combo_tbl.creator=combo_tbl.ref_user"
					." AND combo_tbl.deleted=0"
					."{$fltr['WHERE']['alb']}";
	$qu['ORDER'] = 	"(rnk-display) ASC";
}

if (!empty($_GET['spec']))
{	$spec = explode(".",$_GET['spec']);
 	$qu['WHERE'] .= " AND {$path['db']}img.time=".intval($spec[1]);
}

$qu['final'] = "SELECT {$qu['SELECT']}{$qu['CNTR']} FROM {$qu['FROM']} WHERE {$qu['WHERE']} ORDER BY {$qu['ORDER']}";



/*
	$qu = 	"SELECT {$path['db']}img.* FROM {$path['db']}img, {$path['db']}albms"
			." WHERE {$path['db']}albms.album='{$alb}'"
			." AND {$path['db']}img.time={$path['db']}albms.ref_time"
			." AND {$path['db']}img.creator={$path['db']}albms.ref_user"
			." AND {$path['db']}img.deleted=0"
			." ORDER BY {$path['db']}img.time DESC";
			
*/			
//	die($qu);		
	$data = mysql_query($qu['final'],$connection);		
			
	echo "\n <meta exported=\"".date("Y-m-d H:i:s",$tm)."\" album_id=\"{$alb}\">";
		
	for ($i = 0; $i < mysql_num_rows($data); $i++)
	{
		$img[$i] = mysql_fetch_array($data);
		
		$cp[$i] = mysql_fetch_array(mysql_query("SELECT updated, caption, version_tag FROM {$path['db']}cptn WHERE usr='{$img[$i]['creator']}' AND time={$img[$i]['time']} AND versioned=0",$connection));
		$mt[$i] = mysql_fetch_array(mysql_query("SELECT * FROM {$path['db']}meta WHERE creator='{$img[$i]['creator']}' AND time={$img[$i]['time']}",$connection));		
		
		if (!empty($cp[$i]['caption']))
		{
			echo "\n  <photo"
				." id=\"{$img[$i]['creator']}.{$img[$i]['time']}\""
//				." last_updated=\"".date("Y-m-d H:i:s",$img[$i]['time'])."\""
				.">"
					."\n   <caption"
//						." version=\"\""
						." last_updated=\"".date("Y-m-d H:i:s",$cp[$i]['updated'])."\""
							.">"
							. xml_clean_simple($cp[$i]['caption'])
						."</caption>"
					."\n   <credit"
						." photographer=\"".xml_clean_simple($mt[$i]['aao_credit'])."\""
						." affiliation=\"".xml_clean_simple($mt[$i]['aao_src'])."\""
							." />"	
					."\n   <rating"
						." last_updated=\"".date("Y-m-d H:i:s",$img[$i]['rating_time'])."\""
						." value=\"".intval($img[$i]['rating'])."\""
							." />"					
					."\n  </photo>"
				;
		}
	}
	
	echo "\n </meta>";
}

echo "\n</editor>";
?>
