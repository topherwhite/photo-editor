<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
header('Cache-Control: no-cache, must-revalidate'); header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once("../../inc/php/vars.inc.php");
require_once("../../inc/php/ws_proc.inc.php");

if ($con = mysql_conn($var))
{
	$ws = check_supplied_vars($input_vars,$var,$con);
	
	if ($ws['value'] != "log") { record_to_log("list",$ws,$con); }
	
	if (empty($ws['value'])) { $ws['value'] = "bucket"; }
	
	$qu[0] = "SELECT * FROM editor.{$ws['value']}";
	if (($ws['value'] == "bucket") && !empty($ws['project_id'])) { $qu[0] .= " WHERE project_id='{$ws['project_id']}'"; }
	$qu[0] .= " ORDER BY created DESC LIMIT {$var['ws']['return_limit']}";
		
	$rtrn = rows_to_array(array("action"=>$ws['action'],"datatype"=>$ws['value'],"query"=>$qu),$con,$ws);
}
mysql_close($con);

echo output_xml_json($rtrn,$input_vars,$var);

?>