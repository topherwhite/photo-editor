
var popup_background = 0;
// UI material
function img_swap(id,wh) { if (wh == 'on') { var addition = "_"; var minus = 0; } else if (wh == 'off') { var addition = ""; var minus = 1; } if ((document.getElementById(id) != null)&&(document.getElementById(id).style.backgroundImage != null)) { var img_url = document.getElementById(id).style.backgroundImage.substring(1+document.getElementById(id).style.backgroundImage.indexOf("(")).substring(0,document.getElementById(id).style.backgroundImage.substring(1+document.getElementById(id).style.backgroundImage.indexOf("(")).lastIndexOf(")")).replace(/\"/g,"").replace(/\'/g,""); var img_base = img_url.substring(0,img_url.lastIndexOf(".")-minus); var img_ext = img_url.substring(1+img_url.lastIndexOf(".")); document.getElementById(id).style.backgroundImage = 'url('+img_base+addition+'.'+img_ext+')'; } }
function imgsrc_swap(id,wh) { if (wh == 'on') { var addition = "_"; var minus = 0; } else if (wh == 'off') { var addition = ""; var minus = 1; } if ((document.getElementById(id) != null)&&(document.getElementById(id).src != null)) { var img_base = document.getElementById(id).src.substring(0,document.getElementById(id).src.lastIndexOf(".")-minus); var img_ext = document.getElementById(id).src.substring(1+document.getElementById(id).src.lastIndexOf(".")); document.getElementById(id).src = img_base+addition+'.'+img_ext; } }
function bg_swap(id,clr) { if (clr != 'trans') { document.getElementById(id).style.backgroundColor = '#'+clr; } else { document.getElementById(id).style.backgroundColor = 'transparent'; } }
function brdr_swap(id,wh,color) { var elems = document.getElementsByName(id); for (i=0;i<elems.length;i=i+1) { if (wh == 'on') { var brdr = "solid 1px " + color; } else if (wh == 'off') { var brdr = "solid 1px transparent"; } elems[i].style.border = brdr; } }
function js_on_enter(e,js) { var keycode; if (window.event) keycode = window.event.keyCode; else if (e) keycode = e.which; else return true; if (keycode == 13) { var t = setTimeout(js, 25); return false; } else { return true; } }
function get_scroll_x() { var x = window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft; return x ? x : 0; }
function get_scroll_y() { var y = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop; return y ? y : 0; }
function hilite(needle,haystack) { var srch_for = needle.toLowerCase(); var srch_in = haystack.toLowerCase(); var out = ''; if (srch_in.indexOf(srch_for) >= 0) { out += haystack.substring(0,srch_in.indexOf(srch_for))+'<span class="hi">'+haystack.substring(srch_in.indexOf(srch_for),srch_in.indexOf(srch_for)+srch_for.length)+'</span>'+haystack.substring(srch_in.indexOf(srch_for)+srch_for.length); } else { out += haystack; } return out; }
function is_email(str) { var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; return emailPattern.test(str); }

var ajax_retry_interval = 5000; var override_video_mode = '';
// AJAX material
var xmlHttp; var ajax_use_archive = 0; var popup_drag_update_freq = 25; var global_xpos = 0; var global_ypos = 0; var mousedown_status = false; document.onmousemove = mouseMove; document.onmouseup = set_mouseup;
function GetXmlHttpObject() { var xmlHttp=null; try { xmlHttp=new XMLHttpRequest(); } catch (e) { try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); } } return xmlHttp; }
function Matrix() { this.r = new Array(); }
function mouseCoords(ev) { if (ev.pageX || ev.pageY) { return {x:ev.pageX, y:ev.pageY}; } return {x:ev.clientX+document.body.scrollLeft-document.body.clientLeft, y:ev.clientY+document.body.scrollTop-document.body.clientTop}; }
function mouseMove(ev) { ev = ev || window.event; var target   = ev.target || ev.srcElement; var mousePos = mouseCoords(ev); global_xpos = mousePos.x; global_ypos = mousePos.y; }
function set_mouseup() { mousedown_status = false; }
function move_popup_start(popup_id) { mousedown_status=true; document.getElementById(popup_id+'_buffer').style.visibility = 'visible'; move_popup(global_xpos,global_ypos,popup_id); }
function move_popup(xpos,ypos,popup_id) { if (mousedown_status == true) { document.getElementById(popup_id).style.left = parseInt(document.getElementById(popup_id).style.left)+(global_xpos-xpos)+'px'; document.getElementById(popup_id).style.top = parseInt(document.getElementById(popup_id).style.top)+(global_ypos-ypos)+'px'; var t = setTimeout("move_popup("+global_xpos+","+global_ypos+",'"+popup_id+"')", popup_drag_update_freq); } else {	document.getElementById(popup_id+'_buffer').style.visibility = 'hidden'; } }
function popup_destroy(id) { document.getElementById('popup_box_'+id).innerHTML = ''; if (document.getElementById('popup_box_location_'+id) == null) { /*alert('popup box location is undefined: '+id);*/ } else if ((document.getElementById('bg_darkener') != null) && (document.getElementById('popup_box_location_'+id).value != 'popup_box_container_univ_extra') && (document.getElementById('popup_box_location_'+id).value.substring(0,18) != 'popup_within_popup')) { document.getElementById('bg_darkener').style.visibility = 'hidden'; } }
function completion_set_popups(id,state) { if (state == '0') { document.getElementById('completion_popup_'+id).value = '0'; } else if (state == '1') { document.getElementById('completion_popup_'+id).value = '1'; } }
function completion_check_start_popups(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st) { var t = setTimeout("completion_check_go_popups('"+pass_wh+"','"+pass_id+"','"+pass_wd+"','"+pass_hr+"','"+pass_vt+"','"+pass_lc+"','"+pass_st+"');", ajax_retry_interval); }
function completion_check_go_popups(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st) { if ((document.getElementById('completion_popup_'+pass_wh+'_'+pass_id) != null) && (document.getElementById('completion_popup_'+pass_wh+'_'+pass_id).value == '0')) { req_popup_setup(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st); } }

function search_sub()
{ 	if (document.getElementById('m_search_str').value.length < 4) { alert('You must enter at least 4 characters into the search field. Please try again.'); }
	else
	{ 	if (document.getElementById('m_search_slct').options[document.getElementById('m_search_slct').selectedIndex].value == 'staff') { var wh = 'staff'; var wd = '265'; }
	 	else if (document.getElementById('m_search_slct').options[document.getElementById('m_search_slct').selectedIndex].value == 'www') { var wh = 'www'; var wd = '320'; }
		req_popup_setup('search', wh, wd, 390-wd,'-80','www');
	}
}


function popup_box_html(wh,id,wd,hr,vt,lc,st)
{
	var box_id = wh+'_'+id;
	var marg = 33;
	var brdr_wd = parseInt(wd);
	var brdr_left = (marg-1);
	var crnr_method = 'img';
	var crnr_t_class = '';
	var crnr_b_class = '';
	
	if ( 	((BrowserDetect.browser == 'Chrome') && (BrowserDetect.version >= 5))
			||	((BrowserDetect.browser == 'Safari') && (BrowserDetect.version >= 5))
			||	((BrowserDetect.browser == 'Opera') && (BrowserDetect.version >= 10.5))
			||	((BrowserDetect.browser == 'Firefox') && (BrowserDetect.version >= 3.5))
			) { brdr_wd = parseInt(wd)+2*marg-2; brdr_left = -1; crnr_method = 'css'; crnr_t_class = ' brdr_t_css'+st; crnr_b_class = ' brdr_b_css'+st; }
	
		if (st == '_hard') { brdr_wd = parseInt(wd)+2*marg-2; brdr_left = -1; }
		
		if (wh == 'image') {  document.getElementById('popup_box_container_univ').innerHTML = ''; document.getElementById('popup_box_container_univ').style.top = (120+get_scroll_y())+'px'; }
		else { document.getElementById('popup_box_container_univ').style.top = '120px'; }
		
		var drag_buffer_outer = '<img src="'+iter_pre_url+'media/all/img/stat/trans.gif" id="popup_box_'+box_id+'_buffer" style="visibility:hidden;position:absolute;border:none;left:-'+(1*marg)+'px;top:-'+(2*marg)+'px;width:'+(parseInt(wd)+(4*marg))+'px;height:'+(parseInt(wd)+(4*marg))+'px;background-color:transparent;z-index:0;" />';
		if (BrowserDetect.browser == 'Firefox') { drag_buffer_outer = '<div id="popup_box_'+box_id+'_buffer" style="visibility:hidden;position:absolute;border:none;left:-'+(1*marg)+'px;top:-'+(2*marg)+'px;width:'+(parseInt(wd)+(4*marg))+'px;height:'+(parseInt(wd)+(4*marg))+'px;background-color:transparent;z-index:0;"></div>'; }
		
		var boxtxt = '<div class="popup_box" id="popup_box_'+box_id+'" style="width:'+(parseInt(wd)+(2*marg))+'px;left:0px;top:0px;visibility:visible;position:absolute;">'
		 +'<input type="hidden" id="completion_popup_'+box_id+'" value="0" />'
		 +'<input type="hidden" id="reload_popup_'+box_id+'" value="req_popup_setup(\''+wh+'\',\''+id+'\',\''+wd+'\',\''+hr+'\',\''+vt+'\',\''+lc+'\',\''+st+'\')" />'
		 +'<div class="brdr_t'+st+crnr_t_class+'" style="width:'+brdr_wd+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');">'
		if ((st != '_hard') && (crnr_method == 'img')) { boxtxt += 	'<div class="crnr_tl"><img src="'+iter_pre_url+'media/all/img/stat/popup/crnr_gr_all.png" /></div><div class="crnr_tr" style="left:'+(parseInt(wd)-1)+'px;"><img style="left:-33px;" src="'+iter_pre_url+'media/all/img/stat/popup/crnr_gr_all.png" /></div>'; }
		 boxtxt +='</div>'
		 	+'<div class="popup_box_x_container" unselectable="on"'
				+' onClick="popup_destroy(\''+box_id+'\');"'
				+' onMouseOver="imgsrc_swap(\'popup_box_'+box_id+'_x\',\'on\')" onMouseOut="imgsrc_swap(\'popup_box_'+box_id+'_x\',\'off\')"'
				+' style="top:5px;left:'+(parseInt(wd)+marg-5)+'px;">'
			+'<img class="popup_box_x'+st+'" id="popup_box_'+box_id+'_x" unselectable="on"'
		//		+' onClick="popup_destroy(\''+box_id+'\');"'
		//		+' onMouseOver="imgsrc_swap(\'popup_box_'+box_id+'_x\',\'on\')" onMouseOut="imgsrc_swap(\'popup_box_'+box_id+'_x\',\'off\')"'
		//		+' style="top:5px;left:'+(parseInt(wd)+marg-5)+'px;"'
		//		+'background-image:url('+iter_pre_url+'media/all/img/stat/popup/x'+st+'.png);'
		//		+'"></div>'
				+' src="'+iter_pre_url+'media/all/img/stat/popup/x'+st+'.png"'
				+' />'
		 		+'</div>'
		 +'<div class="inner" id="popup_inner_'+box_id+'" style="width:'+(parseInt(wd)+2*marg-2)+'px;padding-left:0px;padding-right:0px;">'
		 +'<table style="width:100%;background-color:transparent;z-index:5;border:none;" id="popup_table_'+box_id+'">'
		 +'<tr>'
		 +'<td style="width:'+marg+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');"></td><td id="popup_all_'+box_id+'">'

		 +'<br /><img src="'+iter_pre_url+'media/all/img/stat/anim/loading.gif" style="position:relative;left:'+Math.round(2*parseInt(wd)/5)+';top:0px;width:'+Math.round(parseInt(wd)/5)+'px;cursor:pointer;border:none;" onClick="req_popup_setup(\''+wh+'\',\''+id+'\',\''+wd+'\',\''+hr+'\',\''+vt+'\',\''+lc+'\',\''+st+'\');" alt="Click to reload..." />'

		 +'</td><td style="width:'+marg+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');"></td>'
		 +'</tr>'
		 +'<tr><td></td><td class="popup_addons" id="popup_addons"></td><td></td></tr>'
		 +'</table>'
		 +'<div class="brdr_container" style="border:none;width:0px;height:0px;position:relative;z-index:4;">'
		 +'<div class="brdr_b'+st+crnr_b_class+'" style="position:absolute;left:'+brdr_left+'px;top:0px;width:'+brdr_wd+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');">';
		if ((st != '_hard') && (crnr_method == 'img')) { boxtxt += 	'<div class="crnr_bl"><img style="top:-33px;" src="'+iter_pre_url+'media/all/img/stat/popup/crnr_gr_all.png" /></div><div class="crnr_br" style="left:'+(parseInt(wd)-1)+'px;"><img style="top:-33px;left:-33px;" src="'+iter_pre_url+'media/all/img/stat/popup/crnr_gr_all.png" /></div>'; }
		boxtxt += '</div>'
		 +'</div>'
		 +'</div>'
		 + drag_buffer_outer
		 +'</div>';
	
	return boxtxt;
}


function req_popup_setup(wh,id,wd,hr,vt,lc,st)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }

	if (lc == null) { var lc = 'all'; }
	
	if (st == null) { var st = ''; } else if (st == 'hard') { var st = '_hard'; }
	
	var box_id = wh+'_'+id;
	
	var lang = ""; if (document.getElementById('global_lang') != null) { lang = document.getElementById('global_lang').value; }
	
	var urldepth = iter_pre_url.split('/').length-1;
	
	url = iter_pre_url+'ajax/'+lc+'/'+wh+'/wd_'+parseInt(wd)+'/lang_'+lang+'/urldepth_'+urldepth+'/id_'+escape(id).replace(/\*/g,'$');
	
	if ( (wh == 'email_subscribe') || (wh == 'search') || (wh == 'newsline_search')
		) { url = iter_pre_url+'media/'+lc+'/ajax/xml/ajax_'+wh+'.php?id='+escape(id)+'&wd='+parseInt(wd)+'&lang=' + lang +'&key=' + Math.random(); }

	if ( wh == 'html' ) { url += '?key='+Math.random(); }
	if ( wh == 'depts') { window.open(url); }
//	if (ajax_use_archive == 1) { url += '&archive=1'; }

	if ( (parseInt(wd) == 0) && (parseInt(hr) == 0) && (parseInt(vt) == 0) ) {  }
	else
	{	if 	(	(document.getElementById('popup_box_location_'+box_id) == null)
			|| 	(document.getElementById(document.getElementById('popup_box_location_'+box_id).value) == null)
			)	{ alert('popup box location is undefined: '+box_id); }
		else
		{	document.getElementById(document.getElementById('popup_box_location_'+box_id).value).innerHTML = popup_box_html(wh,id,wd,hr,vt,lc,st);
			document.getElementById('popup_box_'+box_id).style.left = parseInt(hr)+'px';
			document.getElementById('popup_box_'+box_id).style.top = parseInt(vt)+'px';
			document.getElementById('popup_box_'+box_id).style.position = 'absolute';
			document.getElementById('popup_box_'+box_id).style.zIndex = '16';
			
			// dark background or no
			if	(	(wh == 'sp2010iframe') || ( (wh == 'html') &&
					(	(id.substring(0,id.indexOf('-')) == 'story') || (id == 'story')
					||	(id == 'all_iter') || (id == 'marketplace') || (id == 'events')
					))
					|| ( (popup_background != 0) && (popup_background == 1))
				)
			{	document.getElementById('bg_darkener').style.visibility = 'visible'; }
		}
	}
	
	var rtrn_func = "stateChng_"+wh;
	if ((wh == 'search') && ((id == 'staff') || (id == 'www'))) { rtrn_func += "_"+id; }
	xmlHttp.onreadystatechange = eval(rtrn_func);	
		
	if (wh != 'search')
	{	xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
	else
	{	var m = new Matrix(); m.r[0] = new Array('srch'); m.r[1] = new Array(document.getElementById('m_search_str').value);	
		var bnd_str = "*-YWC-*"; var bnd = "--"+bnd_str; var req_bdy = bnd;
		for (i = 0; i < m.r[0].length; i = i+1) { req_bdy += "\nContent-Disposition: form-data; name=\""+m.r[0][i]+"\""+"\n\n"+m.r[1][i]+"\n"+bnd; }					
		xmlHttp.open("POST",url,true); xmlHttp.setRequestHeader("Content-type","multipart/form-data; boundary=\""+bnd_str+"\"");
		xmlHttp.setRequestHeader("Content-length",req_bdy.length); xmlHttp.setRequestHeader("Connection","close"); xmlHttp.send(req_bdy);
	}

	completion_set_popups(box_id,'0'); //completion_check_start_popups(wh,id,wd,hr,vt,lc,st);
	
}

// popup append permalink
function append_permalink(wh,id,on_off)
{	
	if (on_off == 1)
	{	var extra = '<input type="text" class="permalink_field" value="http://www.iter.org/video/'+id+'" />'
					+'<img onClick="javascript:append_permalink(\''+wh+'\',\''+id+'\',0);" src="'+iter_pre_url+'media/all/img/stat/popup/x.png" style="width:10px;height:10px;" />';
		document.getElementById('popup_addons').style.height = '20px';
		document.getElementById('popup_addons').innerHTML = extra;
	}
	else
	{	document.getElementById('popup_addons').innerHTML = '';
		document.getElementById('popup_addons').style.height = '0px';
	}
}

// display video popup
function stateChng_video()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var vid_id = parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('id'));
		var vid_wd = parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'));
		var vid_ht = parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'));
		var date_length = parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('time'));
		var date_year = xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y');
		var date_month = xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m');
		var vid_ttl = xmlDoc.getElementsByTagName('vid')[0].getAttribute('titl').replace(/"/gi,'');
		
		video_format = 'flv';
		var embedcode = '<embed type="application/x-shockwave-flash" src="'+iter_pre_url+'media/all/vid/player.swf"'
			+' style="'+'width:'+vid_wd+'px;'+'height:'+vid_ht+'px;'+'background-color:#ffffff;'+'"'
			+' id="mpl"'+' name="mpl" quality="high" allowscriptaccess="always" allowfullscreen="true"'+' flashvars="'
				+'file=../../../vid/all/'+date_year+'/'+date_month+'/'+vid_id+'.'+video_format
	//			+'&image='+iter_pre_url+'media/img/dyn/vid_screenshot.php?id='+vid_id+'&yr='+date_year+'&wd='+vid_wd+'&ht='+vid_ht+'&type=.jpg'
				+'&autostart=true'+'&bufferlength=3'+'&controlbar=bottom'+'&displayclick=play'+'&fullscreen=true'+'&type=video'+'&icons=true'
				+'&repeat=none'+'&resizing=true'+'&respectduration=false'+'&smoothing=true'+'&stretching=uniform'+'&volume=75'
				+'&duration='+date_length+'&width='+vid_wd+'&height='+vid_ht
				+'"'+' width="'+vid_wd+'"'+' height="'+vid_ht+'"'+' bgcolor="#ffffff"'+' wmode="opaque"'
				+'></embed>';
	
		var embedcode_legacy = '<embed type="application/x-shockwave-flash" src="'+iter_pre_url+'media/all/vid/player_legacy.swf?'
				+'file=../../../vid/all/'+date_year+'/'+date_month+'/'+vid_id+'.flv'
				+'&autoStart=true'+'"'+' style="'+'width:'+vid_wd+'px;'
				+'height:'+(vid_ht+20)+'px;'+'background-color:#ffffff;'+'"'
			+'></embed>';
			
		var embedcode_noflash = '<span style="font-size:120%;font-weight:bold;">'
								+'We have detected that <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash Player</a> is not installed or has been disabled on your computer. Unfortunately, that means you will be unable to play this movie directly in the page.'
								+'<br /><br />We also offer our videos in mp4 format. Click the link below to load the mp4 version of the video in a new browser window, or to download the video directly to your computer.'
								+'<br /><br />'
								+'<a target="_blank"'
									+' href="'+iter_pre_url+'vid/all/'+date_year+'/'+date_month+'/'+vid_id+'.mp4"'
									+' style="">'
								+'Video: '+vid_ttl
								+'</a></span>';
		
		var embedcode_html5 = '<video'
								+' src=\"'+iter_pre_url+'vid/all/'+date_year+'/'+date_month+'/'+vid_id+'.mp4\"'
								+' width=\"'+vid_wd+'\"'
								+' height=\"'+vid_ht+'\"'
								+' controls=\"controls\" autoplay=\"autoplay\"'
								+'>'
					//			+'<source width=\"'+vid_wd+'\"'
					//			+' height=\"'+vid_ht+'\"'
					//			+' src=\"'+iter_pre_url+'vid/all/'+date_year+'/'+date_month+'/'+vid_id+'.mp4\"'
					//			+' />'
								+'</video>';
		
		var player_switch = '';
		var player_switch_to_html5 = 'override_video_mode=\'html5\';var t=setTimeout(\''+document.getElementById('reload_popup_video_'+vid_id).value.replace(/'/g,"\\'").replace(/"/g,'\\"')+'\',50);';
		var player_switch_to_flash = 'override_video_mode=\'flash\';var t=setTimeout(\''+document.getElementById('reload_popup_video_'+vid_id).value.replace(/'/g,"\\'").replace(/"/g,'\\"')+'\',50);';

		var embedcode_html5_incomp = '<div style="width:100%;font-size:14px;padding:10px 0px 10px 0px;text-align:center;">'
									+'We&#39;re sorry to inform you that your browser ('+BrowserDetect.browser+' '+BrowserDetect.version+') is not compatible with our implementation of HTML5 video (H.264).'
									+'<br /><br />This is unfortunate, since the open-standard of HTML5 video offers many advantages over the proprietary Flash video standard, and we encourage you to upgrade/switch browsers to one which support HTML5 (H.264). More information can be found <a target="_blank" class="lnk" href="http://en.wikipedia.org/wiki/HTML5_video#Browser_support">here</a>.'
									+'<br /><br />Please click <a class="lnk" onClick="'+player_switch_to_flash+'">here</a> or on the link below to view this video using the Flash player.'
									+'</div>';
		
		var ga_tag = 'unknown';
		
		if	( 	(override_video_mode == 'html5')
			||	((BrowserDetect.browser == 'Safari') && (override_video_mode == ''))
			//||	(BrowserDetect.browser == 'Chrome')
			) { if	(	((BrowserDetect.browser == 'Explorer') && (BrowserDetect.version < 9))
					||	((BrowserDetect.browser == 'Chrome') && (BrowserDetect.version < 3))
					||	((BrowserDetect.browser == 'Safari') && (BrowserDetect.version < 4))
					||	((BrowserDetect.browser == 'Opera') && (BrowserDetect.version < 10))
					||	((BrowserDetect.browser == 'Firefox') && (BrowserDetect.version < 4))
					)			{ var emb_code = embedcode_html5_incomp; 	player_switch = '<a onClick="'+player_switch_to_flash+'">use flash player</a>'; ga_tag = 'html5_incompatible' }
				else 					{ var emb_code = embedcode_html5; 	player_switch = '<a onClick="'+player_switch_to_flash+'">use flash player</a>'; ga_tag = 'html5'; }
			}
		else if (FlashVersion == 0)		{ var emb_code = embedcode_noflash; player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; ga_tag = 'no_flash'; }
		else if (FlashVersion >= 10)
										{ var emb_code = embedcode; 		player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; ga_tag = 'flash'; }
		else 							{ var emb_code = embedcode_legacy;	player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; ga_tag = 'flash_legacy'; }
		
		emb_code += '<br />'
					+'<table class="popup_add_ons"><tr>'
					+'<td class="add_on_left">'+player_switch+'</td>'
					+'<td class="add_on_middle">'
							+'<a'
							+' href="javascript:append_permalink(\'video\',\''+vid_id+'\',1);"'
							+' onClick="_gaq.push([\'_trackEvent\',\'video\', \'show_permalink\', \''+vid_id+'\']);"'
							+'>show video perma-link</a>'
					+'</td>'
					+'<td class="add_on_right">'
							+'<a target="_blank"'
							+' onClick="_gaq.push([\'_trackEvent\',\'video\', \'high_res_download\', \''+vid_id+'\']);"'
							+' href="ftp://ftp.iter.org/permanent_files/io_in_cad/com/vid_hi_res/'+date_year+'/'+vid_id+'.avi.zip">'
					+'download high-res</a>'
					+'</td>'
					+'</tr></table>'
					;

		document.getElementById('popup_all_video_'+vid_id).innerHTML = emb_code;
		completion_set_popups('video_'+vid_id,'1');
		_gaq.push(['_trackEvent','video', 'viewed', vid_id]);
	}
}


function stateChng_popups()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var id = xmlDoc.getElementsByTagName('popups')[0].getAttribute('id');
		var innertxt = '';
		
		if (xmlDoc.getElementsByTagName('popups')[0].getAttribute('ttl') != '')
		{ innertxt += '<span style="font-weight:bold;font-size:16px;">'
					+xmlDoc.getElementsByTagName('popups')[0].getAttribute('ttl')
					+'</span>'; }
					
		if (xmlDoc.getElementsByTagName('popups')[0].getAttribute('bdy') != '')
		{ innertxt += '<br /><br /><span style="font-size:12px;">'
					+xmlDoc.getElementsByTagName('popups')[0].getAttribute('bdy')
					+'</span>'; }

		document.getElementById('popup_all_popups_'+id).innerHTML = innertxt;
		
		completion_set_popups('popups_'+id,'1');
		
		_gaq.push(['_trackEvent','popup', id.substring(0,id.lastIndexOf('_')), id.substring(1+id.lastIndexOf('_')), 0]);
	}
}


function stateChng_image()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		if (	(document.getElementById('popup_inner_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')) != null)
			&&	(parseInt(document.getElementById('popup_inner_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')).style.width) == 704)
			&&	(xmlDoc.getElementsByTagName('img')[0].getAttribute('wd') != 640)
			)
		{	var t = setTimeout( document.getElementById('popup_box_retry_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')).value.replace(',\'640\',',',\''+xmlDoc.getElementsByTagName('img')[0].getAttribute('wd')+'\','), 10);
		}
		
		else
		{	var img_code = ' ';
			
			if (xmlDoc.getElementsByTagName('img')[0].getAttribute('ttl') != '')
			{ img_code += '<span class="ttl">'+xmlDoc.getElementsByTagName('img')[0].getAttribute('ttl')+'</span><br /><br />'; }
		
			img_code += ''
						+'<a target="_blank" href="'+iter_pre_url+'doc'+xmlDoc.getElementsByTagName('img')[0].getAttribute('loc')+'">'
						+'<img src="'+iter_pre_url+'img/sq-'+xmlDoc.getElementsByTagName('img')[0].getAttribute('sq')+'-85'+xmlDoc.getElementsByTagName('img')[0].getAttribute('loc')
						+'" style="border:none;'
						+'width:'+xmlDoc.getElementsByTagName('img')[0].getAttribute('wd')+'px;'
						+'height:'+xmlDoc.getElementsByTagName('img')[0].getAttribute('ht')+'px;'
						+'" />'
						+'</a>'
						;
		
			if (xmlDoc.getElementsByTagName('img')[0].getAttribute('desc') != '')
			{ img_code += '<br /><br /><span class="bdy">'+xmlDoc.getElementsByTagName('img')[0].getAttribute('desc')+'</span>'; }				
		
			document.getElementById('popup_all_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')).innerHTML = img_code;
		
			completion_set_popups('image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id'),'1');
			
			_gaq.push(['_trackEvent','image', 'popup', xmlDoc.getElementsByTagName('img')[0].getAttribute('id').replace(/'/gi,"`") , 0]);
		}
	}
}



function stateChng_search_staff()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var boxtxt = '';
		
		for (i = 0; i < xmlDoc.getElementsByTagName('pers').length; i = i+1)
		{	if (i != 0) { boxtxt += '<br /><hr /><br />'; }
			
			boxtxt += 
				'<b>'+xmlDoc.getElementsByTagName('pers')[i].getAttribute('nm')+'</b>'
				+'<div style="position:relative;left:-4px;border:none;margin-top:5px;height:18px;width:260px;background-image:url('+iter_pre_url+'txt.aspx?str=9_260_111111_ffffff_arial_e-mail:%20'+escape(xmlDoc.getElementsByTagName('pers')[i].getAttribute('em')) +');"></div>'
			//	+'<div style="position:relative;left:-4px;border:none;height:18px;width:260px;background-image:url(/txt.aspx?str=9_260_111111_ffffff_arial_phone:%20%2B'+escape(xmlDoc.getElementsByTagName('pers')[i].getAttribute('ph')) +');"></div>'
			;
		}
		
		if (xmlDoc.getElementsByTagName('pers').length == 0) { boxtxt = 'Your search returned no results...'; }
		
		document.getElementById('popup_all_search_staff').innerHTML = boxtxt;
		
		completion_set_popups('search_staff','1');
		
		_gaq.push(['_trackEvent','search', 'staff', document.getElementById('m_search_str').value.replace(/'/gi,"`") , 0]);
	}
}

function stateChng_search_www()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var boxtxt = '';
		
		var page = xmlDoc.getElementsByTagName('page');
		
		if (page.length == 0) { boxtxt = 'Your search returned no results...'; }
		
		else
		{	for (var i = 0; i < xmlDoc.getElementsByTagName('page').length; i = i+1)
			{	if (i != 0) { boxtxt += '<br /><br />'; }
			
				var url = ' '; if (xmlDoc.getElementsByTagName('page')[i].getAttribute('url') != '') { url = xmlDoc.getElementsByTagName('page')[i].getAttribute('url'); }
				var ttl = ' '; if (xmlDoc.getElementsByTagName('page')[i].getAttribute('ttl') != '') { ttl = xmlDoc.getElementsByTagName('page')[i].getAttribute('ttl'); }
				var bdy = ' '; if (xmlDoc.getElementsByTagName('page')[i].getAttribute('bdy') != '') { bdy = xmlDoc.getElementsByTagName('page')[i].getAttribute('bdy'); }
			
				boxtxt += ''
					+'<a style="color:#21368c;font-weight:bold;float:left;clear:left;" href="'+url+'">'
					+ttl
				//	+url
					+'</a>'
					+'<br /><span style="font-size:80%;color:#666666;float:left;clear:left;cursor:pointer;" onClick="location=\''+url+'\';">'
					+bdy
					+'<br /><span style="text-decoration:underline;">http://www.iter.org'+url+'</span>'
					+'<br /><br /></span>'
					;
			}
		}
				
		document.getElementById('popup_all_search_www').innerHTML = boxtxt;
		
		completion_set_popups('search_www','1');
		
		_gaq.push(['_trackEvent','search', 'www', document.getElementById('m_search_str').value.replace(/'/gi,"`") , 0]);
	}
}

function stateChng_email_subscribe_popup()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var list = xmlDoc.getElementsByTagName('email_subscribe_popup')[0].getAttribute('list');
		
		email_subscribe_text(list);
		
		completion_set_popups('email_subscribe_popup_'+list,'1');
	}
}

function email_subscribe_text(list)
{		
		var email_pre_fill = '';
		if ( (list == 'all_iter') || (list == 'events') || (list == 'marketplace') || (list == 'buzz_stories'))
		{	if ((iter_user_email != null) && (iter_user_email != '')) { email_pre_fill = iter_user_email; }
		}
		
		var out_txt = ''
		+'<a class="ttl">subscribe to &#39;'+list.replace(/_/g,' ')+'&#39; by e-mail'
		+'<span style="font-weight:normal;font-size:80%;"><br /><br />If you would prefer to be notified by <span style="font-weight:bold;">e-mail</span>, please submit your e-mail address below.</span>'
		+'</a>'
		+'<br /><br />'
		+'<input class="form_txt" type="text" name="'+list+'_subscribe_email" id="'+list+'_subscribe_email" value="'+email_pre_fill+'" style="width:230px;" onKeyPress="return js_on_enter(event,\'req_email_subscribe(\\\''+list+'\\\');\')" />'
		+'<br /><br />'
		+'<span id="'+list+'_subscribe_sub_area">'
		+'<input class="form_sub" type="button" name="'+list+'_subscribe_sub" id="'+list+'_subscribe_sub" value="click to submit e-mail address" onClick="req_email_subscribe(\''+list+'\');" />'
		+'<img src="'+iter_pre_url+'media/all/img/stat/trans.gif" class="load" onLoad="document.getElementById(\''+list+'_subscribe_email\').focus();" />'
		+'</span>';
	if ( (list == 'all_iter') || (list == 'events') || (list == 'marketplace') || (list == 'buzz_stories'))
	{	out_txt += '<br /><br /><hr /><br />'
		+'<a class="ttl">subscribe to &#39;'+list.replace(/_/g,' ')+'&#39; by rss feed'
		+'<span style="font-weight:normal;font-size:80%;"><br /><br />If you would prefer to be notified by <span style="font-weight:bold;">rss feed</span>, you may use the link below.</span>'
		+'</a>'
		+'<br /><br />'
		+'<a style="color:#21368c;font-weight:bold;font-size:120%;" href="'+iter_pre_url+'feeds/'+list+'" target="_blank">view the &#39;'+list.replace(/_/g,' ')+'&#39; rss feed...</a>'
		;
	}
	
	document.getElementById('popup_all_email_subscribe_popup_'+list).innerHTML = out_txt;
}

function req_email_subscribe(list)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	url = iter_pre_url+"media/all/ajax/ajax_email_control/?action=subscribe&list="+escape(list)+"&val="+escape(document.getElementById(list+'_subscribe_email').value)+"&key=" + Math.random();
//	alert('We apologize for the inconvenience, but the e-mail subscribe mechanism is currently disabled for maintenance.\n\nWe expect to have it back up within the next few minutes.\n\nPlease check back soon.');
//	window.open(url);
	xmlHttp.onreadystatechange = stateChng_email_subscribe; xmlHttp.open("GET",url,true); xmlHttp.send(null);
	document.getElementById(list+'_subscribe_sub_area').innerHTML = 'please wait a moment...';
}

function stateChng_email_subscribe()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var list = xmlDoc.getElementsByTagName('email_subscribe')[0].getAttribute('list');
		var err = xmlDoc.getElementsByTagName('email_subscribe')[0].getAttribute('error');
		var email = xmlDoc.getElementsByTagName('email_subscribe')[0].getAttribute('email');
		
		if (err == 'none')
		{
			document.getElementById('popup_all_email_subscribe_popup_'+list).innerHTML =
				'<a class="ttl">&#39;'+email+'&#39;<br /> was successfully subscribed<br />to the mailing list!'
				+'<br /><br />You should receive an e-mail confirmation momentarily.'
				+'<br /><br />Be sure to follow the link in that e-mail to confirm your subscription.</a>'
				+'<br /><br />'
				+'<span id="'+list+'_subscribe_sub_area">'
				+'<input class="form_sub" type="button" name="'+list+'_subscribe_sub" id="'+list+'_subscribe_sub" value="close window" onClick="popup_destroy(\'email_subscribe_popup_'+list+'\');" />'
				+'</span>'
				;
	//		_gaq.push(['_trackEvent','subscribe','subscribe_email','subscribe_email_'+list]);
		}
		else if (err == 'exists')
		{
			document.getElementById('popup_all_email_subscribe_popup_'+list).innerHTML =
				'<a class="ttl">&#39;'+email+'&#39;<br /> is already on the &#39;'+list.replace(/_/g,' ')+'&#39; mailing list.</a>'
				+'<br /><br />'
				+'<span id="'+list+'_subscribe_sub_area">'
				+'<input class="form_sub" type="button" name="'+list+'_subscribe_sub" id="'+list+'_subscribe_sub" value="submit a different e-mail address" onClick="email_subscribe_text(\''+list+'\');" />'
				+'</span>'
				;
		}
		else if (err == 'emailbadform')
		{
			document.getElementById(list+'_subscribe_sub_area').innerHTML = '<input class="form_sub" type="button" name="'+list+'_subscribe_sub" id="'+list+'_subscribe_sub" value="click to submit" onClick="req_email_subscribe(\''+list+'\');" />';
			alert(	'It appears that the e-mail address you\'ve submitted is invalid. Please submit a valid e-mail address.');
		}
		
	}
}

function stateChng_html()
{	if (xmlHttp.readyState == 4)
	{	var id = xmlHttp.responseText.substring(0,xmlHttp.responseText.indexOf('**YWC**'));
		document.getElementById('popup_all_html_'+id).innerHTML = xmlHttp.responseText.substring(7+xmlHttp.responseText.indexOf('**YWC**'));
}	}

function stateChng_people_search_popup()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var list = xmlDoc.getElementsByTagName('people_search_popup')[0].getAttribute('list');
		
		var search_pre_fill = '';
		//if ( (list == 'all_iter') || (list == 'events') || (list == 'marketplace'))
		//{	if ((iter_user_email != null) && (iter_user_email != '')) { email_pre_fill = iter_user_email; }
		//}
		
		var out_txt = ''
		+'<a class="ttl">Search for anyone in the ITER directory...'
	//	+'<span style="font-weight:normal;font-size:80%;"><br /><br />If you would prefer to be notified by <span style="font-weight:bold;">e-mail</span>, please submit your e-mail address below.</span>'
		+'</a>'
		+'<br /><br />'
		+'<div class="search_icon_holder" style="margin-left:0px;margin-top:1px;top:4px;"><img src="'+iter_pre_url+'media/all/img/stat/icon/icon_search_60.png" onClick="document.getElementById(\''+list+'_people_search\').focus()" onLoad="document.getElementById(\''+list+'_people_search\').focus()" /></div>'
		+'<input class="form_txt people_search" type="text" name="'+list+'_people_search" id="'+list+'_people_search" value="'+search_pre_fill+'" style="width:90%;" onKeyup="req_people_search_value_clear(\''+list+'\');" />'
		+'<div class="search_clear_holder" style="margin-top:1px;top:0px;"><img id="'+list+'_people_search_clear" class="search_clear_bttn" onMouseOver="imgsrc_swap(\''+list+'_people_search_clear\',\'on\');" onMouseOut="imgsrc_swap(\''+list+'_people_search_clear\',\'off\');" src="'+iter_pre_url+'media/all/img/stat/popup/x_gr.png" style="top:4px;" onClick="req_people_search_search_clear(\''+list+'\',\'\');" title="clear search field..." /></div>'
		+'<br /><br /><div id="'+list+'_people_search_sub_area"></div>';
				
		document.getElementById('popup_all_people_search_popup_'+list).innerHTML = out_txt;
		
		completion_set_popups('people_search_popup_'+list,'1');
	}
}

function convert_currency()
{
	
}