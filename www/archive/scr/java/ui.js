function thbhvr(action,ident)
{	
	if (action == 1)
	{
		document.getElementById('cell_'+ident).style.backgroundColor = '#'+rate_clr_hovr[document.getElementById('rate_'+ident).options[document.getElementById('rate_'+ident).selectedIndex].value];
		document.getElementById('opt1_'+ident).style.visibility = 'visible';
		document.getElementById('opt2_'+ident).style.visibility = 'visible';
	}
	
	else if (action == 2)
	{
		document.getElementById('cell_'+ident).style.backgroundColor = '#'+rate_clr_flat[document.getElementById('rate_'+ident).options[document.getElementById('rate_'+ident).selectedIndex].value];
		document.getElementById('opt1_'+ident).style.visibility = 'hidden';
		document.getElementById('opt2_'+ident).style.visibility = 'hidden';
	}
	
	thmb_bulge(0,action,ident);
}

function thmb_bulge(nm,action,ident)
{	
	var nxt = nm + 1;
	
	if (action == 2) { var oper_size = (0-(2*nxt)); var oper_pos = nxt; }
	if (action == 1) { var oper_size = (2*nxt); var oper_pos = (0-nxt); }
	
	document.getElementById('thmb_'+ident).style.left = (parseInt(document.getElementById('thmb_'+ident).style.left)+oper_pos) + "px";
	document.getElementById('thmb_'+ident).style.top = (parseInt(document.getElementById('thmb_'+ident).style.top)+oper_pos) + "px";
	document.getElementById('thmb_'+ident).style.width = (parseInt(document.getElementById('thmb_'+ident).style.width)+oper_size) + "px";
	document.getElementById('thmb_'+ident).style.height = (parseInt(document.getElementById('thmb_'+ident).style.height)+oper_size) + "px";

	if (nm < thmb_bulge_magnitude) { var t = setTimeout("thmb_bulge("+ nxt + "," + action + ","+ident+");",33); }
	
}

function imghvr(action,id_name)
{	
	if (action == 1)		{ var minus = 4; var addition = "_"; }
	else if (action == 2)	{ var minus = 5; var addition = ""; }
	
	document.getElementById(id_name).src =
		document.getElementById(id_name).src.substring(0,(document.getElementById(id_name).src.length-minus))
				+ addition + '.' + document.getElementById(id_name).src.substring((document.getElementById(id_name).src.length-3),(document.getElementById(id_name).src.length));
}




function slct_checkbox(action,i)
{	
	if (action == 0) { var slct_chck = false; var cell_brdr = '#'+chck_clr_flat[0]; }
	else if (action == 1) { var slct_chck = true; var cell_brdr = '#'+chck_clr_flat[1];; }
	else if (action == 3) { if (document.getElementById('slct_'+i).checked == false) { var cell_brdr = '#'+chck_clr_flat[0]; } else { var cell_brdr = '#'+chck_clr_flat[1]; } }
	else if (action == 4) {		if (document.getElementById('slct_'+i).checked == false) { var slct_chck = true; var cell_brdr = '#'+chck_clr_flat[1]; }
								else { var slct_chck = false; var cell_brdr = '#'+chck_clr_flat[0]; } }
	
	if (action != 3) { document.getElementById('slct_'+i).checked = slct_chck; }
	document.getElementById('cell_'+i).style.border = '1px solid ' + cell_brdr;
}

function change_all(which,action)
{	
	if (which == 'slct') { for (i = 0; i < nmbr_visible; i = i + 1) { slct_checkbox(action,i); } }
	
	else if (which == 'rate') { for (i = 0; i < nmbr_visible; i = i + 1) { set_dflt_rating(i); } }
	
	else if (which == 'status') { for (i = 0; i < nmbr_visible; i = i + 1) { set_dflt_status(i); } }
	
	else if (which == 'orig_hires') { for (i = 0; i < nmbr_visible; i = i + 1) { set_dflt_orig_is_hires(i); } }
	
}

function set_dflt_rating(ident)
{	
	document.getElementById('rate_'+ident).selectedIndex = document.getElementById('dflt_rate_'+ident).value;
}

function set_dflt_status(ident)
{	
	document.getElementById('status_'+ident).selectedIndex = document.getElementById('dflt_status_'+ident).value;
}

function set_dflt_orig_is_hires(ident)
{	
	document.getElementById('orig_hires_'+ident).checked = document.getElementById('dflt_orig_hires_'+ident).value;
}

function open_win(action,ident)
{	
	if (action == 'hi_res') { var wd = 300; var ht = 300; var url = '/scr/php/sub_high_res.php?usr='+document.getElementById('val_usr_'+ident).value+'&wh='+document.getElementById('val_wh_'+ident).value+'&key='+Math.random(); }
	
//	alert(url);
		
	
	window.open(	url, '_blank', 
					'toolbar=no,location=no,directories=no,status=no,menubar=no,'
					+'scrollbars=no,resizable=no,copyhistory=no,'
					+'width='+wd+',height='+ht);	
	
}



function set_all_photos() 	{ document.getElementById('view_slct').selectedIndex = 0; }

function set_spec_img() 	{ document.getElementById('get_spec_val').value = document.getElementById('url_val_spec').value; }


//Go to Album
function change_view(view)
{
	location = '?view='+view
				+'&alb='+document.getElementById('view_slct').options[document.getElementById('view_slct').selectedIndex].value
				+'&fltr='+document.getElementById('url_val_fltr').value
				+'&spec='+document.getElementById('get_spec_val').value
				+'&style='+document.getElementById('style_slct').options[document.getElementById('style_slct').selectedIndex].value
				;
}


function fltr_view(wh)
{
	
	if (	(document.getElementById('fltr_slct').options[document.getElementById('fltr_slct').selectedIndex].value != '-')
		&&	(document.getElementById('fltr_slct_val').value != '')
		&&	(wh == 1)
		)
	{	location = 	'?view=1'
					+'&alb='+escape(document.getElementById('url_val_alb').value)
					+'&style='+escape(document.getElementById('url_val_style').value)
					+"&fltr="+document.getElementById('fltr_slct').options[document.getElementById('fltr_slct').selectedIndex].value
						+"*"+escape(document.getElementById('fltr_slct_val').value);
	}
	else if ( wh == 0 )
	{	location = '?view=1'
					+'&alb='+escape(document.getElementById('url_val_alb').value)
					+'&style='+escape(document.getElementById('url_val_style').value);
	}
	else
	{	alert('Please make sure that you have selected a field a value to filter by before submitting...');
	}
}


function fill_meta(ref_key)
{	
	var send_to = document.getElementById('sl_'+ref_key).options[document.getElementById('sl_'+ref_key).selectedIndex].value;
		
	document.getElementById('wr_'+send_to).value = document.getElementById('pre_val_'+ref_key).innerHTML;
}

function photo_metadata_submit()
{	
	document.getElementById('photo_metadata').submit()
}