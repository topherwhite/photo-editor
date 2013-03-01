<?php

function popup_open($slct_id,$which_box,$zindex)
{	
	
	return "\n<div class=\"popup_{$which_box}_{$slct_id}\" id=\"popup_{$which_box}_{$slct_id}\" style=\"visibility:hidden;"
					."z-index:{$zindex};\">"
			."<img class=\"popup_x\" id=\"popup_{$which_box}_{$slct_id}_x\" src=\"/img/static/popup/x.png\""
				." onMouseOver=\"imghvr(1,'popup_{$which_box}_{$slct_id}_x')\""
				." onMouseOut=\"imghvr(2,'popup_{$which_box}_{$slct_id}_x')\""
				." onClick=\"popup(0,{$slct_id},'{$which_box}');\""
				." />";
}


function popup_close()
{
	return "</div>";
}

function popup_bttn($slct_id,$which_box)
{
	return "popup(1,{$slct_id},'{$which_box}')";
}

?>