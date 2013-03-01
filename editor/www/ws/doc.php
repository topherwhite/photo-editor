<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
header('Cache-Control: no-cache, must-revalidate'); header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once("../../inc/php/vars.inc.php");
require_once("../../inc/php/ws_proc.inc.php");
require_once("../../inc/php/ws_queries.inc.php");

$proj_id = ""; if (!empty($_GET['project_id'])) { $proj_id = $_GET['project_id']; }
$user_id = ""; if (!empty($_GET['user_id'])) { $user_id = $_GET['user_id']; }
$ws_id = ""; if (!empty($_GET['ws'])) { $ws_id = $_GET['ws']; }
$action_id = ""; if (!empty($_GET['action'])) { $action_id = $_GET['action']; }
$value_id = ""; if (!empty($_GET['value'])) { $value_id = $_GET['value']; }

if ($con = mysql_conn($var))
{
	$bucket_exec = mysql_query("SELECT bucket_id, title FROM editor.bucket WHERE project_id='{$proj_id}' ORDER BY created DESC",$con);
	for ($i = 0; $i < mysql_num_rows($bucket_exec); $i++) { $bucket_arr[$i] = mysql_fetch_array($bucket_exec); }
	
	$user_exec = mysql_query("SELECT * FROM editor.user ORDER BY created DESC",$con);
	for ($i = 0; $i < mysql_num_rows($user_exec); $i++) { $user_arr[$i] = mysql_fetch_array($user_exec); }
	
	$project_exec = mysql_query("SELECT * FROM editor.project ORDER BY created DESC",$con);
	for ($i = 0; $i < mysql_num_rows($project_exec); $i++) { $project_arr[$i] = mysql_fetch_array($project_exec); }
	
	$origin_exec = mysql_query("SELECT * FROM editor.origin ORDER BY created DESC",$con);
	for ($i = 0; $i < mysql_num_rows($origin_exec); $i++) { $origin_arr[$i] = mysql_fetch_array($origin_exec); }
	
	// project_id dropdown
	$proj_html = "<tr><td><label for=\"project_id\">project_id: </label></td><td>"
				."<select name=\"project_id\" id=\"project_id\" onChange=\"update_url_box();\">"
				."<option value=\"\">-choose a project_id-</option>";
		foreach ($project_arr as $val)
		{	$proj_html .= "<option value=\"{$val['project_id']}\"";
			if ($val['project_id'] == $proj_id) { $proj_html .= " selected=\"selected\""; }
			$proj_html .= ">{$val['title']} ({$val['project_id']})</option>";
		}
	$proj_html .= "</select></td></tr>";
	
	// user_id dropdown
	$user_html = "<tr><td><label for=\"user_id\">user_id: </label></td><td>"
				."<select name=\"user_id\" id=\"user_id\" onChange=\"update_url_box();\">"
				."<option value=\"\">-choose a user_id-</option>";
		foreach ($user_arr as $val)
		{	$user_html .= "<option value=\"{$val['user_id']}\"";
			if ($val['user_id'] == $user_id) { $user_html .= " selected=\"selected\""; }
			$user_html .= ">{$val['fullname']} ({$val['user_id']})</option>";
		}			
	$user_html .= "</select></td></tr>";	
	
	// web-service dropdown
	$ws_html = "<tr class=\"web_service_select\"><td><label for=\"ws\">web service: </label></td><td>"
				."<select name=\"ws\" id=\"ws\" onChange=\"interface_reload();\">"
				."<option value=\"\">-choose a web service-</option>";
	foreach ($var['ws']['ws'] as $ws)
	{	$ws_html .= "<option value=\"{$ws}\"";
		if ($ws == $ws_id) { $ws_html .= " selected=\"selected\""; }
		$ws_html .= ">{$ws}</option>";
	}
	$ws_html .= "</select></td></tr>";	
	
	// update->action dropdown
	$action_html = "";
	if ($ws_id == "update")
	{	$action_html = "<tr><td><label for=\"action\">action: </label></td><td>"
				."<select name=\"action\" id=\"action\" onChange=\"interface_reload();\">"
				."<option value=\"\">-choose an action-</option>";
		foreach ($var['ws']['action'] as $action)
		{	$action_html .= "<option value=\"{$action}\"";
			if ($action == $action_id) { $action_html .= " selected=\"selected\""; }
			$action_html .= ">{$action}</option>";
		}
		$action_html .= "</select></td></tr>";	
	}
	
	// update->ids
	$ids_html = "";
	if	(
		( 	($ws_id == "update")
		&&	(	($action_id == "bucket_image_add")
			||	($action_id == "bucket_image_remove")
			||	($action_id == "bucket_image_rank")
			||	($action_id == "bucket_delete")
			||	($action_id == "rate")
			||	($action_id == "position_scale")
			||	($action_id == "rename")
			)
		)
		|| ($ws_id == "search")|| ($ws_id == "meta")
		)
	{	$ids_html = "<tr><td><label for=\"value\">id(s): </label></td><td>";
		for ($i = 0; $i < 10; $i++)
		{	$ids_html .= "<input type=\"text\" size=\"5\" maxlength=\"5\" name=\"ids{$i}\" id=\"ids{$i}\" value=\"\" onKeyUp=\"update_url_box();\" style=\"margin-right:5px;\" />";
		}	
		$ids_html .= "</td></tr>";	
	}	
	
	// action->value dropdown
	$value_text_html = "";
	if	( 	($ws_id == "update")
		&&	( 	($action_id == "bucket_create")
		 	||	($action_id == "rename")
		 	||	($action_id == "bucket_image_rank")
			)
		)
	{	$value_text_html = "<tr><td><label for=\"value\">value: </label></td><td>"
				."<input type=\"text\" size=\"25\" maxlength=\"100\" name=\"value\" id=\"value\" value=\"\" onKeyUp=\"update_url_box();\" />"
				."</td></tr>";	
	}
	if	( 	($ws_id == "update")
		&&	( 	($action_id == "position_scale")
			)
		)
	{	$value_text_html = "<tr><td><label for=\"value\">value: </label></td><td>"
				."<input type=\"text\" size=\"50\" maxlength=\"100\" name=\"value\" id=\"value\" value=\"x,y,z,wd,ht\" onKeyUp=\"update_url_box();\" />"
				."</td></tr>";	
	}	
	
	// action->value (bucket_id)
	$value_choose_html = "";
	if	( 	($ws_id == "update")
		&& (	($action_id == "bucket_image_add")
			||	($action_id == "bucket_image_remove")
			)
		)
	{	$value_choose_html = "<tr><td><label for=\"value\">value: </label></td><td>"
					."<select name=\"value\" id=\"value\" onChange=\"update_url_box();\">"
					."<option value=\"\">null</option>";
			foreach ($bucket_arr as $val)
			{	$value_choose_html .= "<option value=\"{$val['bucket_id']}\">".substr($val['title'],0,10)." ({$val['bucket_id']})</option>";
			}	
		$value_choose_html .= "</select></td></tr>";	
	}
	

	// action->rate (0-5 rating)
	if	( ($ws_id == "update") && ($action_id == "rate") )
	{	$value_choose_html = "<tr><td><label for=\"value\">value: </label></td><td>"
					."<select name=\"value\" id=\"value\" onChange=\"update_url_box();\">"
				."<option value=\"\">null</option>"
				."<option value=\"0\">0 (unrated)</option>"
				."<option value=\"1\">1</option>"
				."<option value=\"2\">2</option>"
				."<option value=\"3\">3</option>"
				."<option value=\"4\">4</option>"
				."</select></td></tr>";	
	}	


	// list->value dropdown
	$list_html = "";
	if ($ws_id == "list")
	{	$list_html = "<tr><td><label for=\"list\">value: </label></td><td>"
				."<select name=\"list\" id=\"list\" onChange=\"update_url_box();\">";
		foreach ($var['ws']['list'] as $list)
		{	$list_html .= "<option value=\"{$list}\"";
			if ($list == $value_id) { $list_html .= " selected=\"selected\""; }
			$list_html .= ">{$list}</option>";
		}
		$list_html .= "</select></td></tr>";	
	}
	
	// search->rating dropdown
	$rating_html = "";
	if ($ws_id == "search")
	{	$rating_html = "<tr><td><label for=\"rating\">rating: </label></td><td>";
		for ($i = 0; $i < 5; $i++)
		{	$rating_html .= "<select name=\"rating{$i}\" id=\"rating{$i}\" onChange=\"update_url_box();\">"
				."<option value=\"\">null</option>"
				."<option value=\"0\">0 (unrated)</option>"
				."<option value=\"1\">1</option>"
				."<option value=\"2\">2</option>"
				."<option value=\"3\">3</option>"
				."<option value=\"4\">4</option>"
				;
			$rating_html .= "</select>";
		}
		$rating_html .= "</td></tr>";
	}
	
	// search->bucket_id dropdown
	$bucket_html = "";
	if (	($ws_id == "search")
		||	(($ws_id == "update") && ($action_id == "position_scale"))
		||	(($ws_id == "update") && ($action_id == "bucket_image_rank"))
		)
	{	$bucket_html = "<tr><td><label for=\"bucket_id\">bucket_id: </label></td><td>";
		for ($i = 0; $i < 5; $i++)
		{	$bucket_html .= "<select name=\"bucket_id{$i}\" id=\"bucket_id{$i}\" onChange=\"update_url_box();\">"
				."<option value=\"\">null</option>";
			foreach ($bucket_arr as $val)
			{	$bucket_html .= "<option value=\"{$val['bucket_id']}\">".substr($val['title'],0,10)." ({$val['bucket_id']})</option>";
			}	$bucket_html .= "<option value=\"trashed\">- trashed -</option>";
				$bucket_html .= "<option value=\"nobucket\">- nobucket -</option>";
			$bucket_html .= "</select>";
		}
		$bucket_html .= "</td></tr>";
	}
	
	// search->origin_id dropdown
	$origin_html = "";
	if ($ws_id == "search")
	{	$origin_html = "<tr><td><label for=\"origin_id\">origin_id: </label></td><td>";
		for ($i = 0; $i < 5; $i++)
		{	$origin_html .= "<select name=\"origin_id{$i}\" id=\"origin_id{$i}\" onChange=\"update_url_box();\">"
				."<option value=\"\">null</option>";
			foreach ($origin_arr as $val)
			{	$origin_html .= "<option value=\"{$val['origin_id']}\">".substr($val['title'],0,10)." ({$val['origin_id']})</option>";
			}	
			$origin_html .= "</select>";
		}
		$origin_html .= "</td></tr>";
	}				


}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>doc</title>
<style type="text/css">
	body { font-family:courier; font-size:14px; }
	option, select { font-family:courier; font-size:14px; }
	label { float:right; }
	tr.web_service_select td { padding-bottom:15px; }
	.url_box { position:relative; width:96%; left:1%; border:solid 1px gray; font-family:courier; font-size:14px; padding:1%; }
	.exec_bttn { position:relative; width:10%; left:1%; border:solid 1px gray; font-family:courier; font-size:14px; padding:1%; }
	.ws_xml { position:relative; width:96%; height:auto; left:1%; border:solid 1px gray; font-family:courier; font-size:10px; color:black; overflow:visible; padding:1%; }
</style>
<script type="text/javascript">
	function get_ws() { return document.getElementById('ws').options[document.getElementById('ws').selectedIndex].value; }
	function get_url()
	{	var out = '';
		if (document.getElementById('project_id').selectedIndex != 0) { out += '?project_id='+document.getElementById('project_id').options[document.getElementById('project_id').selectedIndex].value; }
		if (document.getElementById('user_id').selectedIndex != 0) { out += '&user_id='+document.getElementById('user_id').options[document.getElementById('user_id').selectedIndex].value; }
		if ((document.getElementById('action') != null) && (document.getElementById('action').selectedIndex != 0)) { out += '&action='+document.getElementById('action').options[document.getElementById('action').selectedIndex].value; }
		if (document.getElementById('list') != null) { out += '&value='+document.getElementById('list').options[document.getElementById('list').selectedIndex].value; }
		if ((document.getElementById('value') != null) && (document.getElementById('value').value != '')) { out += '&value='+escape(document.getElementById('value').value); }
		out += 	serialize_params_menu('rating')+serialize_params_menu('bucket_id')
				+serialize_params_menu('origin_id')
				+serialize_ids();
		return out;
	}
	
	function interface_reload()
	{	if ( (document.getElementById('project_id').selectedIndex == 0) || (document.getElementById('user_id').selectedIndex == 0) )
		{ document.getElementById('ws').selectedIndex = 0; alert('The \'project_id\' and \'user_id\' fields are mandatory.\n\nPlease make sure that you\'ve selected a \'project_id\' and \'user_id\' before attempting to proceed.'); }
		else { location = '/ws/doc'+get_url()+'&ws='+get_ws(); }
	}
	
	function update_url_box()
	{	if ((document.getElementById('ws') != null) && (document.getElementById('ws').selectedIndex != 0))
		{	document.getElementById('url_box').value = '/ws/'+get_ws()+get_url(); }
		else { document.getElementById('url_box').value = ' please select a web-service...'; }
	}
	
	function serialize_params_menu(wh)
	{	var out = "";
		for (var i = 0; i < 20; i=i+1)
		{	if ((document.getElementById(wh+i) != null) && (document.getElementById(wh+i).selectedIndex != 0))
			{	if (out != "") { out += ";"; }
				out += document.getElementById(wh+i).options[document.getElementById(wh+i).selectedIndex].value;
		}	}
		if (out != '') { return '&'+wh+'='+out; } else { return ''; }
	}
	
	function serialize_ids()
	{	var out = "";
		for (var i = 0; i < 20; i=i+1)
		{	if	(	(document.getElementById('ids'+i) != null)
				&& 	(document.getElementById('ids'+i).value != '')
				&& 	(document.getElementById('ids'+i).value.length == 5)
				)
			{	if (out != "") { out += ";"; }
				out += document.getElementById('ids'+i).value;
		}	}
		if (out != '') { return '&ids='+out; } else { return ''; }
	}	
	


	var xmlHttp; function GetXmlHttpObject() { var xmlHttp=null; try { xmlHttp=new XMLHttpRequest(); } catch (e) { try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); } } return xmlHttp; }
	function req_ws(url) { xmlHttp=GetXmlHttpObject(); if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; } xmlHttp.onreadystatechange = stateChng_ws; xmlHttp.open("GET",url,true); xmlHttp.send(null); document.getElementById('ws_xml').innerHTML = 'fetching xml reply...'; }
	function stateChng_ws()
	{	if (xmlHttp.readyState == 4) { document.getElementById('ws_xml').innerHTML =
		xmlHttp.responseText.replace(/>/g,"&gt;").replace(/</g,"&lt;").replace(/'/g,"&apos;").replace(/"/g,"&quot;").replace(/\n /g,"<br />&nbsp;").replace(/\n/g,"<br />");
	}	}
	

</script>
</head>
<body onLoad="update_url_box();">
	<table>
<?php
echo $proj_html
	.$user_html
	.$ws_html
	.$action_html.$ids_html.$value_text_html.$value_choose_html
		.$list_html
		.$rating_html.$bucket_html.$origin_html
	;
?>
	</table>
	<br />
	<input type="text" class="url_box" id="url_box" />
	<br /><input class="exec_bttn" type="button" value="execute" onClick="req_ws(document.getElementById('url_box').value);" />
	<br /><div class="ws_xml" id="ws_xml"></div>
	
</body>
</html>