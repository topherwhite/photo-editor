
// UI material
function img_swap(id,wh) { if (wh == 'on') { var addition = "_"; var minus = 0; } else if (wh == 'off') { var addition = ""; var minus = 1; } var img_url = document.getElementById(id).style.backgroundImage.substring(1+document.getElementById(id).style.backgroundImage.indexOf("(")).substring(0,document.getElementById(id).style.backgroundImage.substring(1+document.getElementById(id).style.backgroundImage.indexOf("(")).lastIndexOf(")")).replace(/\"/g,"").replace(/\'/g,""); var img_base = img_url.substring(0,img_url.lastIndexOf(".")-minus); var img_ext = img_url.substring(1+img_url.lastIndexOf(".")); document.getElementById(id).style.backgroundImage = 'url('+img_base+addition+'.'+img_ext+')'; }
function bg_swap(id,clr) { if (clr != 'trans') { document.getElementById(id).style.backgroundColor = '#'+clr; } else { document.getElementById(id).style.backgroundColor = 'transparent'; } }
function brdr_swap(id,wh,color) { var elems = document.getElementsByName(id); for (i=0;i<elems.length;i=i+1) { if (wh == 'on') { var brdr = "solid 1px " + color; } else if (wh == 'off') { var brdr = "solid 1px transparent"; } elems[i].style.border = brdr; } }
function js_on_enter(e,js) { var keycode; if (window.event) keycode = window.event.keyCode; else if (e) keycode = e.which; else return true; if (keycode == 13) { var t = setTimeout(js, 25); return false; } else { return true; } }

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
function getFlashVersion() { try { try { var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6'); try { axo.AllowScriptAccess = 'always'; } catch(e) { return '6,0,0'; } } catch(e) {} return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1]; } catch(e) { try { if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin) { return (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1]; } } catch(e) { } } return '0,0,0'; } var FlashVersion = getFlashVersion().split(',').shift();
var BrowserDetect = { init: function () { this.browser = this.searchString(this.dataBrowser) || "An unknown browser"; this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version"; this.OS = this.searchString(this.dataOS) || "an unknown OS"; }, searchString: function (data) { for (var i=0;i<data.length;i++)	{ var dataString = data[i].string; var dataProp = data[i].prop; this.versionSearchString = data[i].versionSearch || data[i].identity; if (dataString) { 	if (dataString.indexOf(data[i].subString) != -1) 		return data[i].identity; } else if (dataProp) 	return data[i].identity; } }, searchVersion: function (dataString) { var index = dataString.indexOf(this.versionSearchString); if (index == -1) return; return parseFloat(dataString.substring(index+this.versionSearchString.length+1)); }, dataBrowser: [ { string: navigator.userAgent, subString: "Chrome", identity: "Chrome" }, { 	string: navigator.userAgent, subString: "OmniWeb", versionSearch: "OmniWeb/", identity: "OmniWeb" }, { string: navigator.vendor, subString: "Apple", identity: "Safari", versionSearch: "Version" }, { prop: window.opera, identity: "Opera" }, { string: navigator.vendor, subString: "iCab", identity: "iCab" }, { string: navigator.vendor, subString: "KDE", identity: "Konqueror" }, { string: navigator.userAgent, subString: "Firefox", identity: "Firefox" }, { string: navigator.vendor, subString: "Camino", identity: "Camino" }, {	string: navigator.userAgent, subString: "Netscape", identity: "Netscape" }, { string: navigator.userAgent, subString: "MSIE", identity: "Explorer", versionSearch: "MSIE" }, { string: navigator.userAgent, subString: "Gecko", identity: "Mozilla", versionSearch: "rv" }, {	string: navigator.userAgent, subString: "Mozilla", identity: "Netscape", versionSearch: "Mozilla" } ], dataOS : [ { string: navigator.platform, subString: "Win", identity: "Windows" }, { string: navigator.platform, subString: "Mac", identity: "Mac" }, { string: navigator.userAgent, subString: "iPhone", identity: "iPhone/iPod" }, { string: navigator.platform, subString: "Linux", identity: "Linux" } ] }; BrowserDetect.init();
function popup_destroy(id) { document.getElementById('popup_box_'+id).innerHTML = ''; }
function completion_set_popups(id,state) { if (state == '0') { document.getElementById('completion_popup_'+id).value = '0'; } else if (state == '1') { document.getElementById('completion_popup_'+id).value = '1'; } }
function completion_check_start_popups(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st) { var t = setTimeout("completion_check_go_popups('"+pass_wh+"','"+pass_id+"','"+pass_wd+"','"+pass_hr+"','"+pass_vt+"','"+pass_lc+"','"+pass_st+"');", ajax_retry_interval); }
function completion_check_go_popups(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st) { if ((document.getElementById('completion_popup_'+pass_wh+'_'+pass_id) != null) && (document.getElementById('completion_popup_'+pass_wh+'_'+pass_id).value == '0')) { req_popup_setup(pass_wh,pass_id,pass_wd,pass_hr,pass_vt,pass_lc,pass_st); } }

function req_ws(ws,ids,value)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	url = '/ws/'+ws+'?key=' + Math.random();
	
	xmlHttp.onreadystatechange = stateChng_ws;
	var m = new Matrix();
	m.r[0] = new Array('project_id','user_id','ids','value');
	m.r[1] = new Array(project_id,user_id,ids,value);	
	var bnd_str = "*-YWC-*"; var bnd = "--"+bnd_str; var req_bdy = bnd;
	for (i = 0; i < m.r[0].length; i = i+1) { req_bdy += "\nContent-Disposition: form-data; name=\""+m.r[0][i]+"\""+"\n\n"+m.r[1][i]+"\n"+bnd; }					
	xmlHttp.open("POST",url,true); xmlHttp.setRequestHeader("Content-type","multipart/form-data; boundary=\""+bnd_str+"\"");
	xmlHttp.setRequestHeader("Content-length",req_bdy.length); xmlHttp.setRequestHeader("Connection","close"); xmlHttp.send(req_bdy);
}

function stateChng_ws()
{	if (xmlHttp.readyState == 4)
	{
		//	var xmlDoc=xmlHttp.responseXML.documentElement;
		alert(xmlHttp.responseText);
}	}


/*
function search_sub()
{ 	if (document.getElementById('m_search_str').value.length < 4) { alert('You must enter at least 4 characters into the search field. Please try again.'); }
	else
	{ 	if (document.getElementById('m_search_slct').options[document.getElementById('m_search_slct').selectedIndex].value == 'staff') { var wh = 'staff'; var wd = '265'; }
	 	else if (document.getElementById('m_search_slct').options[document.getElementById('m_search_slct').selectedIndex].value == 'www') { var wh = 'www'; var wd = '320'; }
		req_popup_setup('search', wh, wd, 390-wd,'-80','www');
	}
}


function req_popup_setup(wh,id,wd,hr,vt,lc,st)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }

	if (lc == null) { var lc = 'www'; }
	
	if (st == null) { var st = ''; } else if (st == 'hard') { var st = '_hard'; }
	
	var lang = document.getElementById('global_lang').value;
	
	url = '/media/ajax/'+lc+'/ajax_'+wh+'.php?id='+escape(id)+'&wd='+parseInt(wd)+'&lang=' + lang +'&key=' + Math.random();

	if (ajax_use_archive == 1) { url += '&archive=1'; }

	var box_id = wh+'_'+id;
	var marg = 33;
		
	if ( (parseInt(wd) == 0) && (parseInt(hr) == 0) && (parseInt(vt) == 0) ) {  }
	else
	{	
		var drag_buffer_outer = '<img src="/media/img/stat/trans.gif" id="popup_box_'+box_id+'_buffer" style="visibility:hidden;position:absolute;border:none;left:-'+(1*marg)+'px;top:-'+(2*marg)+'px;width:'+(parseInt(wd)+(4*marg))+'px;height:'+(parseInt(wd)+(4*marg))+'px;background-color:transparent;z-index:0;" />';
		if (BrowserDetect.browser == 'Firefox') { drag_buffer_outer = '<div id="popup_box_'+box_id+'_buffer" style="visibility:hidden;position:absolute;border:none;left:-'+(1*marg)+'px;top:-'+(2*marg)+'px;width:'+(parseInt(wd)+(4*marg))+'px;height:'+(parseInt(wd)+(4*marg))+'px;background-color:transparent;z-index:0;"></div>'; }
		
		var boxtxt = '<div class="popup_box" id="popup_box_'+box_id+'" style="width:'+(parseInt(wd)+(2*marg))+'px;left:0px;top:0px;visibility:visible;position:absolute;">'
		 +'<input type="hidden" id="completion_popup_'+box_id+'" value="0" />'
		 +'<input type="hidden" id="reload_popup_'+box_id+'" value="req_popup_setup(\''+wh+'\',\''+id+'\',\''+wd+'\',\''+hr+'\',\''+vt+'\',\''+lc+'\',\''+st+'\')" />'
		 +'<div class="brdr_t" style="width:'+parseInt(wd)+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');">'
		 +'<div class="crnr_tl'+st+'">';
		if (st != '_hard') { boxtxt += '<img style="" src="/media/img/stat/popup/crnr_gr_all.png" />'; }
		boxtxt += '</div>'
		 +'<div class="crnr_tr'+st+'" style="left:'+(parseInt(wd)-1)+'px;">';
		if (st != '_hard') { boxtxt += '<img style="left:-33px;" src="/media/img/stat/popup/crnr_gr_all.png" />'; }
		boxtxt += '</div>'
		 +'<div class="brdr_line'+st+'" style="width:'+wd+'px;"></div>'
		 +'</div>'
		 +'<div class="popup_box_x'+st+'" id="popup_box_'+box_id+'_x" onClick="popup_destroy(\''+box_id+'\');"'
			//	+' onMouseOver="img_swap(\'popup_box_'+box_id+'_x\',\'on\')" onMouseOut="img_swap(\'popup_box_'+box_id+'_x\',\'off\')"'
				+' style="top:5px;left:'+(parseInt(wd)+marg-5)+'px;"></div>'
		 +'<div class="inner" id="popup_inner_'+box_id+'" style="width:'+(parseInt(wd)+2*marg-2)+'px;padding-left:0px;padding-right:0px;">'
		 +'<table style="width:100%;background-color:transparent;z-index:5;border:none;">'
		 +'<tr>'
		 +'<td style="width:'+marg+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');"></td><td id="popup_all_'+box_id+'">'

		 +'<br /><img src="/media/img/stat/anim/loading.gif" style="position:relative;left:'+Math.round(2*parseInt(wd)/5)+';top:0px;width:'+Math.round(parseInt(wd)/5)+'px;cursor:pointer;" onClick="req_popup_setup(\''+wh+'\',\''+id+'\',\''+wd+'\',\''+hr+'\',\''+vt+'\');" alt="Click to reload..." />'

		 +'</td><td style="width:'+marg+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');"></td>'
		 +'</tr>'
		 +'</table>'
		 +'<div class="brdr_container" style="border:none;width:0px;height:0px;position:relative;z-index:4;">'
		 +'<div class="brdr_b" style="position:absolute;left:'+(marg-1)+'px;top:0px;width:'+parseInt(wd)+'px;cursor:move;" onMouseDown="move_popup_start(\'popup_box_'+box_id+'\');">'
		 +'<div class="crnr_bl'+st+'">';
		if (st != '_hard') { boxtxt += '<img style="top:-33px;" src="/media/img/stat/popup/crnr_gr_all.png" />'; }
		boxtxt += '</div>'
		 +'<div class="crnr_br'+st+'" style="left:'+(parseInt(wd)-1)+'px;">';
		if (st != '_hard') { boxtxt += '<img style="top:-33px;left:-33px;" src="/media/img/stat/popup/crnr_gr_all.png" />'; }
		boxtxt += '</div>';
		if (st != '_hard') { boxtxt += '<div class="brdr_line" style="border-width:0px 0px 1px 0px;"></div>'; }
		boxtxt += '</div>'
		 +'</div>'
		 +'</div>'
		 + drag_buffer_outer
		 +'</div>';


		document.getElementById(document.getElementById('popup_box_location_'+box_id).value).innerHTML = boxtxt;
		document.getElementById('popup_box_'+box_id).style.left = parseInt(hr)+'px';
		document.getElementById('popup_box_'+box_id).style.top = parseInt(vt)+'px';
		document.getElementById('popup_box_'+box_id).style.position = 'absolute';
		document.getElementById('popup_box_'+box_id).style.zIndex = '16';
	}
	
	if (wh == 'image') { url += '&extra=' + document.getElementById('popup_box_extra_'+box_id).value; }
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

	completion_set_popups(box_id,'0'); completion_check_start_popups(wh,id,wd,hr,vt,lc,st);
	
}
*/
/*
// display video popup
function stateChng_video()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		video_format = 'flv';
		var embedcode = '<embed type="application/x-shockwave-flash" src="/media/vid/player.swf"'
			+' style="'+'width:'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'px;'+'height:'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+'px;'+'background-color:#ffffff;'+'"'
			+' id="mpl"'+' name="mpl" quality="high" allowscriptaccess="always" allowfullscreen="true"'+' flashvars="'
				+'file=/vid/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.'+video_format
	//			+'&image=/media/img/dyn/vid_screenshot.php?id='+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'&yr='+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'&wd='+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'&ht='+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+'&type=.jpg'
				+'&autostart=true'+'&bufferlength=3'+'&controlbar=bottom'+'&displayclick=play'+'&fullscreen=true'+'&type=video'+'&icons=true'
				+'&repeat=none'+'&resizing=true'+'&respectduration=false'+'&smoothing=true'+'&stretching=uniform'+'&volume=75'
				+'&duration='+xmlDoc.getElementsByTagName('vid')[0].getAttribute('time')+'&width='+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'&height='+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))
				+'"'+' width="'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'"'+' height="'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+'"'+' bgcolor="#ffffff"'+' wmode="opaque"'
				+'></embed>';
	
		var embedcode_legacy = '<embed type="application/x-shockwave-flash" src="/media/vid/player_legacy.swf?'
				+'file=/vid/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.flv'
				+'&autoStart=true'+'"'+' style="'+'width:'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'px;'
				+'height:'+(parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+20)+'px;'+'background-color:#ffffff;'+'"'
			+'></embed>';
			
		var embedcode_noflash = '<span style="font-size:120%;font-weight:bold;">'
								+'We have detected that <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash Player</a> is not installed or has been disabled on your computer. Unfortunately, that means you will be unable to play this movie directly in the page.'
								+'<br /><br />We also offer our videos in mp4 format. Click the link below to load the mp4 version of the video in a new browser window, or to download the video directly to your computer.'
								+'<br /><br />'
								+'<a target="_blank"'
									+' href="/vid/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.mp4"'
									+' style="">'
								+'Video: '+xmlDoc.getElementsByTagName('vid')[0].getAttribute('titl').replace(/"/gi,'')
								+'</a></span>';
		
		var embedcode_html5 = '<video'
								+' src=\"/vid/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.mp4\"'
								+' width=\"'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'\"'
								+' height=\"'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+'\"'
								+' controls=\"controls\" autoplay=\"autoplay\"'
								+'>'
					//			+'<source width=\"'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('wd'))+'\"'
					//			+' height=\"'+parseInt(xmlDoc.getElementsByTagName('vid')[0].getAttribute('ht'))+'\"'
					//			+' src=\"/vid/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_m')+'/'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.mp4\"'
					//			+' />'
								+'</video>';
		
		var player_switch = '';
		var player_switch_to_html5 = 'override_video_mode=\'html5\';var t=setTimeout(\''+document.getElementById('reload_popup_video_'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')).value.replace(/'/g,"\\'").replace(/"/g,'\\"')+'\',50);';
		var player_switch_to_flash = 'override_video_mode=\'flash\';var t=setTimeout(\''+document.getElementById('reload_popup_video_'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')).value.replace(/'/g,"\\'").replace(/"/g,'\\"')+'\',50);';

		var embedcode_html5_incomp = '<div style="width:100%;font-size:14px;padding:10px 0px 10px 0px;text-align:center;">'
									+'We&#39;re sorry to inform you that your browser ('+BrowserDetect.browser+' '+BrowserDetect.version+') is not compatible with our implementation of HTML5 video (H.264).'
									+'<br /><br />This is unfortunate, since the open-standard of HTML5 video offers many advantages over the proprietary Flash video standard, and we encourage you to upgrade/switch browsers to one which support HTML5 (H.264). More information can be found <a target="_blank" class="lnk" href="http://en.wikipedia.org/wiki/HTML5_video#Browser_support">here</a>.'
									+'<br /><br />Please click <a class="lnk" onClick="'+player_switch_to_flash+'">here</a> or on the link below to view this video using the Flash player.'
									+'</div>';
		
		
		if	( 	(override_video_mode == 'html5')
			||	((BrowserDetect.browser == 'Safari') && (override_video_mode == ''))
			//||	(BrowserDetect.browser == 'Chrome')
			) { if	(	((BrowserDetect.browser == 'Explorer') && (BrowserDetect.version < 9))
					||	((BrowserDetect.browser == 'Chrome') && (BrowserDetect.version < 3))
					||	((BrowserDetect.browser == 'Safari') && (BrowserDetect.version < 4))
					||	((BrowserDetect.browser == 'Opera') && (BrowserDetect.version < 10))
					||	((BrowserDetect.browser == 'Firefox') && (BrowserDetect.version < 4))
					)			{ var emb_code = embedcode_html5_incomp; 	player_switch = '<a onClick="'+player_switch_to_flash+'">use flash player</a>'; }
				else 					{ var emb_code = embedcode_html5; 	player_switch = '<a onClick="'+player_switch_to_flash+'">use flash player</a>'; }
			}
		else if (FlashVersion == 0)		{ var emb_code = embedcode_noflash; player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; }
		else if (FlashVersion >= 10)
										{ var emb_code = embedcode; 		player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; }
		else 							{ var emb_code = embedcode_legacy;	player_switch = '<a onClick="'+player_switch_to_html5+'">use html5 player</a>'; }
		
		emb_code += '<br />'
					+'<table class="vid_add_ons"><tr>'
					+'<td class="vid_player_switch">'+player_switch+'</td>'
					+'<td class="vid_hi_res">'
					+'<a target="_blank" onClick="pageTracker._trackEvent(\'video\', \'high_res_download\', \''+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'\' );"'
					+' href="ftp://ftp.iter.org/permanent_files/io_in_cad/com/vid_hi_res/'
						+xmlDoc.getElementsByTagName('vid')[0].getAttribute('dt_y')+'/'
						+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')+'.avi.zip">'
					+'download high-res</a>'
					+'</td>'
					+'</tr></table>'
					;

		document.getElementById('popup_all_video_'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')).innerHTML = emb_code;
		completion_set_popups('video_'+xmlDoc.getElementsByTagName('vid')[0].getAttribute('id'),'1');
		pageTracker._trackEvent('video', 'viewed', escape(xmlDoc.getElementsByTagName('vid')[0].getAttribute('id')), 0);
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
		
		pageTracker._trackEvent('popup', id.substring(0,id.lastIndexOf('_')), id.substring(1+id.lastIndexOf('_')), 0);
	}
}


function stateChng_image()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		if (	(parseInt(document.getElementById('popup_inner_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')).style.width) == 704)
			&&	(xmlDoc.getElementsByTagName('img')[0].getAttribute('wd') != 640)
			)
		{	var t = setTimeout( document.getElementById('popup_box_retry_image_'+xmlDoc.getElementsByTagName('img')[0].getAttribute('id')).value.replace(',\'640\',',',\''+xmlDoc.getElementsByTagName('img')[0].getAttribute('wd')+'\','), 10);
		}
		
		else
		{	var img_code = ' ';
		
			if (xmlDoc.getElementsByTagName('img')[0].getAttribute('titl') != '')
			{ img_code += '<span class="ttl">'+xmlDoc.getElementsByTagName('img')[0].getAttribute('titl')+'</span><br /><br />'; }
		
			img_code += ''
						+'<a target="_blank" href="'+'/img/orig-1600-100'+xmlDoc.getElementsByTagName('img')[0].getAttribute('loc')+'">'
						+'<img src="/img/sq-'+xmlDoc.getElementsByTagName('img')[0].getAttribute('wd')+'-85'+xmlDoc.getElementsByTagName('img')[0].getAttribute('loc')
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
			
			pageTracker._trackEvent('image', 'popup', xmlDoc.getElementsByTagName('img')[0].getAttribute('id').replace(/'/gi,"`") , 0);
		}
	}
}


function stateChng_announcements()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var boxtxt = '';
		
		if ( (xmlDoc.getElementsByTagName('announcements')[0].getAttribute('img') == null) || (xmlDoc.getElementsByTagName('announcements')[0].getAttribute('img') == '') ) { var img = ''; }
		else { var img = '<img src="/img/sq-160-70'+xmlDoc.getElementsByTagName('announcements')[0].getAttribute('img')+'" style="float:right;" />'; }
		
		boxtxt += img+'<span class="ttl">'+xmlDoc.getElementsByTagName('announcements')[0].getAttribute('titl')+'</span>';
		
		boxtxt += '<span class="bdy"><br /><br />'+xmlDoc.getElementsByTagName('announcements')[0].getAttribute('body')+'</span>'; 
		
		document.getElementById('popup_all_announcements_'+xmlDoc.getElementsByTagName('announcements')[0].getAttribute('id')).innerHTML = boxtxt;
		
		completion_set_popups('announcements_'+xmlDoc.getElementsByTagName('announcements')[0].getAttribute('id'),'1');
		
		pageTracker._trackEvent(xmlDoc.getElementsByTagName('announcements')[0].getAttribute('loc'), 'announcements', xmlDoc.getElementsByTagName('announcements')[0].getAttribute('id') , 0);
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
				+'<div style="position:relative;left:-4px;border:none;margin-top:5px;height:18px;width:260px;background-image:url(/txt.aspx?str=9_260_111111_ffffff_arial_e-mail:%20'+escape(xmlDoc.getElementsByTagName('pers')[i].getAttribute('em')) +');"></div>'
			//	+'<div style="position:relative;left:-4px;border:none;height:18px;width:260px;background-image:url(/txt.aspx?str=9_260_111111_ffffff_arial_phone:%20%2B'+escape(xmlDoc.getElementsByTagName('pers')[i].getAttribute('ph')) +');"></div>'
			;
		}
		
		if (xmlDoc.getElementsByTagName('pers').length == 0) { boxtxt = 'Your search returned no results...'; }
		
		document.getElementById('popup_all_search_staff').innerHTML = boxtxt;
		
		completion_set_popups('search_staff','1');
		
		pageTracker._trackEvent('search', 'staff', document.getElementById('m_search_str').value.replace(/'/gi,"`") , 0);
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
		
		pageTracker._trackEvent('search', 'www', document.getElementById('m_search_str').value.replace(/'/gi,"`") , 0);
	}
}
*/
