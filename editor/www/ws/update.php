<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
header('Cache-Control: no-cache, must-revalidate'); header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once("../../inc/php/vars.inc.php");
require_once("../../inc/php/ws_proc.inc.php");
require_once("../../inc/php/ws_queries.inc.php");

if ($con = mysql_conn($var))
{
	$ws = check_supplied_vars($input_vars,$var,$con);
	
	record_to_log("update",$ws,$con);
	
	if ($ws['action'] == "rate") { $qu = qu_rate($ws); }
	elseif ($ws['action'] == "bucket_create") { $qu = qu_bucket_create(generate_unique_key($con,"bucket","bucket_id",$key_length,1),$ws); }
	elseif ($ws['action'] == "bucket_delete") { $qu = qu_bucket_delete($ws); }
	elseif ($ws['action'] == "bucket_image_add") { $qu = qu_bucket_image_add($ws); }
	elseif ($ws['action'] == "bucket_image_remove") { $qu = qu_bucket_image_remove($ws); }
	elseif ($ws['action'] == "bucket_image_rank") { $qu = qu_bucket_image_rank($ws); }
	elseif ($ws['action'] == "position_scale") { $qu = qu_position_scale($ws); }
//	elseif ($ws['action'] == "rename") { $qu = qu_rename($ws); }
	
	$rtrn = rows_to_array($qu,$con,$ws);
}
mysql_close($con);

echo output_xml_json($rtrn,$input_vars,$var);

?>