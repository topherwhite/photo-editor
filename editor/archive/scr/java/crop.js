
function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	return {
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	};
}

function mouseMove(ev)
{	ev = ev || window.event;

	var target   = ev.target || ev.srcElement;
	var mousePos = mouseCoords(ev);
	
	global_xpos = mousePos.x;
	global_ypos = mousePos.y;	
	
}

document.onmousemove = mouseMove;

function res_w_div(xpos)
{	if (mouse_status == "d")
	{	
		global_wd = parseInt(document.getElementById('temp_div').style.width) + global_xpos - xpos;

		if	(	( (left_pos + global_wd) <= bound_right )
			&&	(global_wd >= minimum_wd)
			)
		{
			document.getElementById('temp_div').style.width = global_wd + "px";		
			
			document.getElementById('temp_div_res_w').style.left = (global_wd - 6) + "px";
			document.getElementById('temp_div_res_h').style.width = (global_wd + 12) + "px";
			document.getElementById('temp_div_res_top').style.width = (global_wd + 12) + "px";

			document.getElementById('drk_r').style.left = (left_pos + global_wd + 2) + "px";
			document.getElementById('drk_r').style.width = (bound_right - left_pos - global_wd) + "px";
			document.getElementById('drk_t').style.width = (global_wd + 2) + "px";
			document.getElementById('drk_b').style.width = (global_wd + 2) + "px";
						
		}
		
		var t = setTimeout("res_w_div("+ global_xpos +")", update_freq);
	}
}

function res_h_div(ypos)
{	if (mouse_status == "d")
	{	
		global_ht = parseInt(document.getElementById('temp_div').style.height) + global_ypos - ypos;

		if	( ( (top_pos + global_ht) <= bound_bottom )
			&&	(global_ht >= minimum_wd)
			)
		{	document.getElementById('temp_div').style.height = global_ht + "px";
			document.getElementById('temp_div_res_w').style.height = (global_ht + 12) + "px";		
			document.getElementById('temp_div_res_h').style.top = (global_ht - 6) + "px";
			document.getElementById('temp_div_res_lft').style.height = (global_ht + 12) + "px";
			document.getElementById('drk_b').style.top = (top_pos + global_ht + 2) + "px";	
			document.getElementById('drk_b').style.height = (bound_bottom - global_ht - top_pos) + "px";
			
		}
		
		var t = setTimeout("res_h_div("+ global_ypos +")", update_freq);
	}
}

function res_lft_div(xpos)
{	if (mouse_status == "d")
	{	
		x_shift = parseInt(document.getElementById('temp_div').style.left) + global_xpos - xpos;
		
		global_wd = parseInt(document.getElementById('temp_div').style.width) - (global_xpos - xpos);
				
		if	( ( x_shift >= bound_left )
			&&	(global_wd >= minimum_wd)
			)
			
		{
			document.getElementById('temp_div').style.width = global_wd + "px";
			document.getElementById('temp_div').style.left = x_shift + "px";
			document.getElementById('temp_div_res_w').style.left = (global_wd - 6) + "px";
			document.getElementById('temp_div_res_h').style.width = (global_wd + 12) + "px";
			document.getElementById('temp_div_res_top').style.width = (global_wd + 12) + "px";
			document.getElementById('drk_l').style.width = (x_shift - bound_left) + "px";
			document.getElementById('drk_t').style.left = (x_shift) + "px";
			document.getElementById('drk_t').style.width = (global_wd + 2) + "px";
			document.getElementById('drk_b').style.left = (x_shift) + "px";
			document.getElementById('drk_b').style.width = (global_wd + 2) + "px";
						
		}
		
		var t = setTimeout("res_lft_div("+ global_xpos +")", update_freq);
	}
}

function res_top_div(ypos)
{	if (mouse_status == "d")
	{	
		y_shift = parseInt(parseInt(document.getElementById('temp_div').style.top) + global_ypos - ypos);
		
		global_ht = parseInt(document.getElementById('temp_div').style.height) - (global_ypos - ypos);
				
		if	( ( y_shift >= bound_top )
			&&	(global_ht >= minimum_wd)
			)
		{
			document.getElementById('temp_div').style.top = y_shift + "px";
			document.getElementById('temp_div').style.height = global_ht + "px";	
			document.getElementById('temp_div_res_w').style.height = (global_ht + 12) + "px";
			document.getElementById('temp_div_res_h').style.top = (global_ht - 6) + "px";
			document.getElementById('temp_div_res_lft').style.height = (global_ht + 12) + "px";
			document.getElementById('drk_t').style.height = (y_shift - bound_top) + "px";
		}
		
		var t = setTimeout("res_top_div("+ global_ypos +")", update_freq);
	}
}



function div_action_start(which)
{	mouse_status = "d";
	
	left_pos = parseInt(document.getElementById('temp_div').style.left);
	top_pos = parseInt(document.getElementById('temp_div').style.top);	
	global_wd = parseInt(document.getElementById('temp_div').style.width);
	global_ht = parseInt(document.getElementById('temp_div').style.height);
	
	if (which == "res_w") { res_w_div(); }	
	if (which == "res_h") { res_h_div(); }
	if (which == "res_lft") { res_lft_div(global_xpos); }	
	if (which == "res_top") { res_top_div(global_ypos); }
	
}

function div_action_end()
{	mouse_status = "u";
	
	var url = "/scr/ajax/crop.php?usr="+editor_usr+"&wh="+editor_wh
				+"&lf=" + parseInt(document.getElementById('temp_div').style.left)
				+"&tp=" + parseInt(document.getElementById('temp_div').style.top)
				+"&wd=" + parseInt(document.getElementById('temp_div').style.width)
				+"&ht=" + parseInt(document.getElementById('temp_div').style.height)
				+"&cn=" + conversion
				+"&key=" + Math.random();
				;
	req_crop_save(url);
}



