<?php

function qu_rate($ws)
{	$qu[0] = "UPDATE editor.image SET rating=".intval($ws['value']).", rating_time=".mktime()." WHERE ";
	foreach ($ws['ids'] as $ind=>$val) { if ($ind != 0) { $qu[0] .= "OR "; } $qu[0] .= "image_id='{$val}' "; }
	
	return array("fields"=>array("rating"=>$ws['value'])
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

function qu_bucket_create($id,$ws)
{	$qu[0] = "INSERT INTO editor.bucket SET bucket_id='{$id}', project_id='{$ws['project_id']}', created=".mktime();
	if (!empty($ws['value'])) { $qu[0] .= ", title='{$ws['value']}'"; $ttl = $ws['value']; } else { $ttl = "untitled bucket"; }
	
	return array("fields"=>array("bucket_id"=>$id,"title"=>stripslashes($ttl),"project_id"=>$ws['project_id'])
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

/*function qu_rename($ws)
{	$table = substr($ws['value'],0,strpos($ws['value'],";"));
	$string = substr($ws['value'],strlen($table)+1);
	
//	$qu[0] = "UPDATE editor.{$table} SET bucket_id='{$id}', project_id='{$ws['project_id']}', created=".mktime();
//	if (!empty($ws['value'])) { $qu[0] .= ", title='{$ws['value']}'"; $ttl = $ws['value']; } else { $ttl = "untitled bucket"; }
	
//	return array("fields"=>array("bucket_id"=>$id,"title"=>stripslashes($ttl),"project_id"=>$ws['project_id'])
//				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}*/

function qu_bucket_delete($ws)
{	$i = 0; foreach ($ws['ids'] as $ind=>$val)
	{	$qu[$i] = "DELETE FROM editor.bucket WHERE bucket_id='{$val}'"; $i++;
		$qu[$i] = "DELETE FROM editor.bucket_image WHERE bucket_id='{$val}'"; $i++;
	}
	
	return array("fields"=>array()
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

function qu_bucket_image_add($ws)
{	$i = 0; foreach ($ws['ids'] as $ind=>$val)
	{	$qu[$i] = "SELECT @rank_cnt := cntr.cnt FROM (SELECT COUNT(*) AS cnt FROM editor.bucket_image WHERE bucket_id='{$ws['value']}') AS cntr;"; $i++;
		$qu[$i] = "INSERT INTO editor.bucket_image SET bucket_id='{$ws['value']}', image_id='{$val}', added=".mktime().", updated=".mktime().", position_x=".round(mt_rand(90-512,512-90)).", position_y=".round(mt_rand(90-384,384-90)).", rank=@rank_cnt+1"; $i++;
		$qu[$i] = "UPDATE editor.image SET trash=0 WHERE image_id='{$val}'"; $i++;
	}
	
	return array("fields"=>array()
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

function qu_bucket_image_remove($ws)
{	$i = 0; foreach ($ws['ids'] as $ind=>$val)
	{	$qu[$i] = "SELECT @rank_last := cntr.last FROM (SELECT rank AS last FROM editor.bucket_image WHERE bucket_id='{$ws['value']}' AND image_id='{$val}') AS cntr;"; $i++;
		$qu[$i] = "DELETE FROM editor.bucket_image WHERE bucket_id='{$ws['value']}' AND image_id='{$val}'"; $i++;
		$qu[$i] = "UPDATE editor.bucket_image SET rank=rank-1 WHERE bucket_id='{$ws['value']}' AND rank>@rank_last"; $i++;
	}
	
	return array("fields"=>array()
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

function qu_bucket_image_rank($ws)
{	$i = 0;
	$qu[$i] = "SELECT @rank_last := cntr.last FROM (SELECT rank AS last FROM editor.bucket_image WHERE bucket_id='{$ws['bucket_id'][0]}' AND image_id='{$ws['ids'][0]}') AS cntr;"; $i++;
	$qu[$i] = "UPDATE editor.bucket_image SET rank=rank-1 WHERE bucket_id='{$ws['bucket_id'][0]}' AND rank>@rank_last"; $i++;
	$qu[$i] = "UPDATE editor.bucket_image SET rank=rank+1 WHERE bucket_id='{$ws['bucket_id'][0]}' AND rank>={$ws['value']}"; $i++;	
	$qu[$i] = "UPDATE editor.bucket_image SET rank={$ws['value']} WHERE bucket_id='{$ws['bucket_id'][0]}' AND image_id='{$ws['ids'][0]}'"; $i++;	

	return array("fields"=>array()
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

function qu_position_scale($ws)
{	$ws_vals = explode(",",$ws['value']);
	
	$qu[0] = "UPDATE editor.bucket_image SET "
				."position_x=".intval(trim($ws_vals[0]))
				.",position_y=".intval(trim($ws_vals[1]))
				.",position_z=".intval(trim($ws_vals[2]))
				.",position_wd=".intval(trim($ws_vals[3]))
				.",position_ht=".intval(trim($ws_vals[4]))
				." WHERE bucket_id='{$ws['bucket_id'][0]}' AND ( ";
	$qu[1] = "UPDATE editor.image SET rotation=".intval(trim($ws_vals[5]))." WHERE ";
	foreach ($ws['ids'] as $ind=>$val) { if ($ind != 0) { $qu[0] .= "OR "; } $qu[0] .= "image_id='{$val}' "; }	$qu[0] .= ")";
	foreach ($ws['ids'] as $ind=>$val) { if ($ind != 0) { $qu[1] .= "OR "; } $qu[1] .= "image_id='{$val}' "; }
		
	return array("fields"=>array()
				,"action"=>$ws['action'],"datatype"=>"query","query"=>$qu);
}

?>