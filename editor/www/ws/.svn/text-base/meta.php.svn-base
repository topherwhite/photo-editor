<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
header('Cache-Control: no-cache, must-revalidate'); header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once("../../inc/php/vars.inc.php");
require_once("../../inc/php/ws_proc.inc.php");

if ($con = mysql_conn($var))
{
	$ws = check_supplied_vars($input_vars,$var,$con);
	
	record_to_log("meta",$ws,$con);
	
	if ( !empty($input_vars['bucket_id']) && ($input_vars["bucket_id"] != "trashed") )
	{	if ($input_vars["bucket_id"] == "nobucket")
		{ 	$qu_from = "editor.image LEFT JOIN (editor.bucket_image) ON (editor.bucket_image.image_id=editor.image.image_id AND editor.bucket_image.bucket_id IS NULL)";
		}
		else
		{	$qu_from = "editor.bucket_image JOIN (editor.image) ON (editor.bucket_image.image_id=editor.image.image_id)";
			$rtrn_fields['bucket_image'] = array("bucket_id","position_x","position_y","position_z","position_wd","position_ht");
		}
	}
	else { $qu_from = "editor.image"; }

	$rtrn_fields['image'] = array("origin_id","width","height","rating","rotation","orig_exif");
	
	$rtrn_fields['qu'] = "image.image_id";
	foreach ($rtrn_fields as $field_ind=>$field_val)
	{ if ($field_ind != "qu"){ foreach ($field_val as $val) { $rtrn_fields['qu'] .= ", {$field_ind}.{$val}"; } } }
	
	$qu[0] = "SELECT {$rtrn_fields['qu']} FROM {$qu_from}"
			." WHERE created_jpg!=1111111111";
	
	if ($input_vars["bucket_id"] != "trashed") { $qu[0] .= " AND trash=0"; }
	else { $qu[0] .= " AND trash=1"; }
	
	foreach ($input_vars as $ind=>$val)
	{	if (!in_array($ind,$var['ws']['action']) && ($ind != "user_id"))
		{	$vals = explode(";",$val);
			if ($ind == "ids") { $ind = "image.image_id"; }
			if (count($vals) == 1)
			{	if ( ($ind != "bucket") && ($val != "trashed") && ($val != "nobucket") )
				{ $qu[0] .= " AND ".mysqlclean($ind,0,$con)."='".mysqlclean($vals[0],0,$con)."'"; }
			}
			else
			{ 	$qu[0] .= " AND (";
					foreach ($vals as $ind_=>$val_)
				{	if ($ind_ != 0) { $qu[0] .= " OR "; }
					$qu[0] .= mysqlclean($ind,0,$con)."='".mysqlclean($val_,0,$con)."'";	
				}	$qu[0] .= ")";
			}
	}	}
	
	
	$qu[0] .= " ORDER BY created DESC LIMIT {$var['ws']['return_limit']}";
	
//	die($qu[0]);
	
	$rtrn = rows_to_array(array("datatype"=>"image","query"=>$qu),$con,$ws);
	
	
}
mysql_close($con);

echo output_xml_json($rtrn,$input_vars,$var);

?>