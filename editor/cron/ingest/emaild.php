#!/usr/bin/php -q
<?php

require_once("/liz/editor/inc/php/vars.inc.php");
require_once("/liz/editor/inc/php/cm/CMBase.php");

function get_CM_list_ids($CM_obj,$vars)
{	$CM_lists = $CM_obj->clientGetLists($vars['creds']['cm']['client_id']);
	$out_array = array();
	foreach ($CM_lists['List'] as $CM_list) { $out_array[$CM_list['Name']] = $CM_list['ListID']; }
	return $out_array;
}

function send_CM_email($CM_obj,$CM_list_id,$CM_data)
{	echo "\n{$CM_data['email']}";
	$sub_rtrn = $CM_obj -> subscriberAddWithCustomFields( $CM_data['email'], $CM_data['fullname'], $CM_data, $CM_list_id, true );
	if ($sub_rtrn['Message'] == "Success")
	{	echo " > subscribed";
		$unsub_rtrn = $CM_obj->subscriberUnsubscribe( $CM_data['email'], $CM_list_id );
		if ($unsub_rtrn['Message'] == "Success") { echo " > un-subscribed"; return true; }
		else { return false; }
	}
	else { return false; }
}


if ($con = mysql_conn($var))
{	mysql_select_db("editor",$con);
	$qu_notif = mysql_query( "SELECT * FROM queue_notifications"
					." LEFT JOIN (user) ON (queue_notifications.user_id=user.user_id)"
					." WHERE queue_notifications.sent=0",$con);
	$cnt_notif = mysql_num_rows($qu_notif);
	if ($cnt_notif > 0)
	{	$CM = new CampaignMonitor( $var['creds']['cm']['api_key'] , $var['creds']['cm']['client_id'] , 0, 0 );
		$CM->method = 'soap'; $CM_lists = get_CM_list_ids($CM,$var);
		for ($i = 0; $i < $cnt_notif; $i++)
		{	$row_notif = mysql_fetch_array($qu_notif);
			unset($CM_data);
			$CM_data['email'] = $row_notif['email'];
			$CM_data['fullname'] = $row_notif['fullname'];
			foreach (json_decode($row_notif['variables']) as $cm_ind=>$cm_val)
			{ $CM_data[$cm_ind] = $cm_val; }
			if (send_CM_email($CM,$CM_lists[$row_notif['template']],$CM_data))
			{	mysql_query("UPDATE editor.queue_notifications SET sent=".mktime()." WHERE rank={$row_notif['rank']}",$con);
	}	}	}
	mysql_close($con);
}


?>