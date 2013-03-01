<?php

require_once("../../inc/php/xml_clean.inc.php");
require_once("../../inc/php/rand.inc.php");

function check_supplied_vars($get_post,$var,$con)
{	
	// error
	$ws['error']['bad_value'] = "";
	
	// action
	$ws['action'] = "";
	if (!empty($get_post['action']))
	{	if (in_array(strtolower($get_post['action']),$var['ws']['action'])) { $ws['action'] = strtolower($get_post['action']); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "action"; }
	}
	
	// id(s)
	$ws['ids'] = array();
	if (!empty($get_post['ids'])) { $ws['ids'] = explode(";",$get_post['ids']); }
	foreach ($ws['ids'] as $ind=>$val)
	{	if (preg_match('/^[a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z]$/',$val))
		{ $ws['ids'][$ind] = strtolower($val); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "ids"; }
	}
	
	// value to apply
	$ws['value'] = "";
	if (empty($ws['action']) && !empty($get_post['value'])){ $ws['value'] = mysqlclean($get_post['value'],0,$con); }
	elseif (!empty($ws['action']) && !empty($get_post['value']))
	{	if (preg_match($var['ws']['value'][$ws['action']],$get_post['value']))
		{	$ws['value'] = mysqlclean($get_post['value'],0,$con);	}
		else { $ws['value'] = "regex failure"; if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "value"; }
	}
	
	// project id
	$ws['project_id'] = "abcde";
	if (!empty($get_post['project_id']))
	{	if (preg_match('/^[a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z]$/',$get_post['project_id']))
		{ $ws['project_id'] = strtolower($get_post['project_id']); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "project_id"; }
	}
	
	// origin_id
	$ws['origin_id'] = "abcde";
	if (!empty($get_post['origin_id']))
	{	if (preg_match('/^[a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z]$/',$get_post['origin_id']))
		{ $ws['origin_id'] = strtolower($get_post['origin_id']); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "origin_id"; }
	}
	
	// project id
	$ws['user_id'] = "abcde";
	if (!empty($get_post['user_id']))
	{	if (preg_match('/^[a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z]$/',$get_post['user_id']))
		{ $ws['user_id'] = strtolower($get_post['user_id']); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "user_id"; }
	}

	// bucket_id(s)
	$ws['bucket_id'] = array();
	if (!empty($get_post['bucket_id'])) { $ws['bucket_id'] = explode(";",$get_post['bucket_id']); }
	foreach ($ws['bucket_id'] as $ind=>$val)
	{	if (preg_match('/^([a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z]|trashed|nobucket)$/',$val))
		{ $ws['bucket_id'][$ind] = strtolower($val); }
		else { if (!empty($ws['error']['bad_value'])) { $ws['error']['bad_value'] .= ";"; } $ws['error']['bad_value'] .= "bucket_id"; }
	}
	
	return $ws;
}

function rows_to_array($qu_arr,$con,$ws)
{	
	$time_offset = intval(date("Z"));
	
if (!empty($ws['error']['bad_value']))
{	$out_arr[0][0] = array("success"=>"false","action"=>$ws['action'],"bad_value"=>$ws['error']['bad_value']);
}
else
{	foreach ($qu_arr['query'] as $qu_ind=>$qu_val)
	{	$out_arr[$qu_ind] = array();
		if (!empty($qu_val) && ($qu_exec = mysql_query($qu_val,$con)))
		{	if (strtoupper(substr($qu_val,0,6)) == "SELECT")
			{	for ($i = 0; $i < mysql_num_rows($qu_exec); $i++)
				{	$v = mysql_fetch_array($qu_exec);
					foreach ($v as $v_ind=>$v_val)
					{	if ( (intval($v_ind) == null) && (strval($v_ind) != "0") )
						{	if ($v_ind == "created") { $v_val = date("Y-m-d\TH:i:s",floor($v_val)-$time_offset)."+0000"; }
							if ($v_ind == "orig_exif")
							{ 	$exif = json_decode($v_val);
								$out_arr[$qu_ind][$i][xml_clean_simple("CHILD_TAG_{$v_ind}")] = "<table class=\"meta\"><tr style=\"font-weight:bold;\"><td class=\"lf ttl\">Field:</td><td class=\"rt ttl\">Value:</td></tr>";
								foreach ($exif as $ex_ind=>$ex_val)
								{ 	if ( ($ex_ind != "ExifTool Version Number") && ($ex_ind != "Directory") && (strpos($ex_val,"use -b option") == 0)
										) { $out_arr[$qu_ind][$i][xml_clean_simple("CHILD_TAG_{$v_ind}")] .= "<tr><td class=\"lf\">{$ex_ind}:</td><td class=\"rt\">{$ex_val}</td></tr>";
										}
								}
								$out_arr[$qu_ind][$i][xml_clean_simple("CHILD_TAG_{$v_ind}")] .= "</table>";
							}
							elseif (strpos($v_ind,":=") == 0) { 
								$out_arr[$qu_ind][$i][xml_clean_simple($v_ind)] = xml_clean_simple($v_val); 
								}
			}	}	}	}
			else
			{	$out_arr[$qu_ind][0] = array("success"=>"true","action"=>$ws['action']);
				foreach ($qu_arr['fields'] as $ind=>$val) { $out_arr[$qu_ind][0][$ind] = xml_clean_simple($val); }
			}
		}
		else
		{	$out_arr[$qu_ind][0] = array("success"=>"false","action"=>$ws['action']);
			foreach ($qu_arr['fields'] as $ind=>$val) { $out_arr[$qu_ind][0][$ind] = xml_clean_simple($val); }
		}
	}
}
	return array("datatype"=>$qu_arr['datatype'],"obj"=>$out_arr);
}


function output_xml_json($obj,$get_post,$vars)
{	
	/*if (!empty($get_post['type']) && ($get_post['type'] == "json"))
	{	header('Content-type: application/json');
		$json_out[$obj['datatype']] = $obj['obj'];
		$out = json_encode($json_out);
	}
	else*/if (empty($get_post['type']) || ($get_post['type'] == "xml"))
	{ 	header('Content-type: text/xml');
		$out = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
				."\n<editor time=\"WEB_SERVICE_EXECUTION_TIME\"";
				foreach ($get_post as $input_ind=>$input_val) { $out .= " ".xml_clean_simple($input_ind)."=\"".xml_clean_simple($input_val)."\""; }
				$out .= ">";
		foreach ($obj['obj'] as $qu_arr)
		{	foreach ($qu_arr as $row_arr)
			{	$out .= "\n <{$obj['datatype']}";
				$has_child = 0;
				foreach ($row_arr as $ind=>$val) { if (substr($ind,0,10) != "CHILD_TAG_") { $out .= " {$ind}=\"{$val}\""; } else { $has_child = 1; } }
				if ($has_child == 0) { $out .= " />"; }
				else
				{	$out .= ">";
					foreach ($row_arr as $ind=>$val)
					{	if (substr($ind,0,10) == "CHILD_TAG_")
						{
							$out .= "<".substr($ind,10).">"."<![CDATA[{$vars['html']['ios']['start']}{$val}{$vars['html']['ios']['end']}]]>"."</".substr($ind,10).">";
						}
					}
					$out .= "</{$obj['datatype']}>";
				}
			}
		}
		$out .= "\n</editor>";
	}
	
	return str_replace("WEB_SERVICE_EXECUTION_TIME",(round(100000*(microtime(true)-$vars['microtime']))/100)."ms",$out);
}


function record_to_log($action,$ws,$con)
{	
	if (!empty($ws['action']) && ($action == "update")) { $action = $ws['action']; }
	
	@mysql_query("INSERT INTO editor.log SET"
				." created='".microtime(true)."'"
				.", user_id='{$ws['user_id']}'"
				.", project_id='{$ws['project_id']}'"
				.", bucket_id='".implode(";",$ws['bucket_id'])."'"
				.", action='{$action}'"
				.", ids='".implode(";",$ws['ids'])."'"
				.", value='{$ws['value']}'"
				,$con);
}


?>