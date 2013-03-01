// AJAX Universal Function (start)
var xmlHttp

function GetXmlHttpObject()
{	var xmlHttp=null;
	try { xmlHttp=new XMLHttpRequest(); }
	catch (e)
	{	try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (e) { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	}
	return xmlHttp;
}
// AJAX Universal Function (end)

function reclean_xml(str)
{
	return str.replace(/&quot;/g,'"').replace(/&gt;/g,'>').replace(/&lt;/g,'<').replace(/&amp;/g,'&').replace(/%u201C/g,'"').replace(/%u201D/g,'"').replace(/%u2013/g,'--').replace(/%u2019/g,'\'').replace(/%u2018/g,'\'').replace(/%u2026/g,'...');
}


// Apply Rating to Thumbnail
function req_rate(ident)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	var url = 	"/scr/ajax/rate.php"
				+"?usr="+document.getElementById('val_usr_'+ident).value
				+"&wh="+document.getElementById('val_wh_'+ident).value
				+"&rt="+document.getElementById('rate_'+ident).options[document.getElementById('rate_'+ident).selectedIndex].value
				+"&key="+Math.random();
				
	xmlHttp.onreadystatechange = stateChng_rate;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_rate()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var succ = parseInt(xmlDoc.getElementsByTagName("rate")[0].childNodes[0].nodeValue);
		if (succ != 1)	{ alert("The rating could not be saved. Please try again..."); }
	}
}

// Update Status
function req_status_update(ident)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	var url = 	"/scr/ajax/status_update.php"
				+"?usr="+document.getElementById('val_usr_'+ident).value
				+"&wh="+document.getElementById('val_wh_'+ident).value
				+"&stat="+document.getElementById('status_'+ident).options[document.getElementById('status_'+ident).selectedIndex].value
				+"&key="+Math.random();
//window.open(url);
	xmlHttp.onreadystatechange = stateChng_status_update;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_status_update()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var rtrn_stat = parseInt(xmlDoc.getElementsByTagName("status")[0].childNodes[0].nodeValue);
		
		for (i = 0; i < nmbr_visible; i = i + 1)
		{	if (	document.getElementById('val_usr_'+i).value == xmlDoc.getElementsByTagName("status")[0].getAttribute("usr")
				&&	document.getElementById('val_wh_'+i).value == xmlDoc.getElementsByTagName("status")[0].getAttribute("wh")
				)
			{
				document.getElementById('status_'+i).selectedIndex = rtrn_stat;
				if (rtrn_stat != 0) 	{ document.getElementById('status_advanced_'+i).style.visibility = 'visible'; }
				else 					{ document.getElementById('status_advanced_'+i).style.visibility = 'hidden'; }
			}
		}
		
		
//		if (succ != 1)	{ alert("The status update could not be saved. Please try again..."); }
	}
}


function confirm_delete(action,ident)
{	if (action == 1)
	{	var r = confirm('Are you sure that you wish to move this photo to the trash?');
		if (r == true) { req_delete(1,ident); }
	}
	else
	{	var r = confirm('Are you sure that you wish to remove this photo from the trash (it will reappear in any relevant albums as well)?');
		if (r == true) { req_delete(0,ident); }
	}
}

function req_delete(action,ident)
{	
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	var url = 	"/scr/ajax/delete.php"
					+"?usr="+document.getElementById('val_usr_'+ident).value
					+"&wh="+document.getElementById('val_wh_'+ident).value
					+"&do="+action+"&key="+Math.random();
				
	xmlHttp.onreadystatechange = stateChng_delete;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_delete()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var succ = parseInt(xmlDoc.getElementsByTagName("delete")[0].childNodes[0].nodeValue);
		if (succ != 1)	{ alert("The image could not be deleted. Please try again..."); }
		else { location.reload(); }
	}
}

//Apply Crops
function req_crop_save(url)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	xmlHttp.onreadystatechange = stateChng_crop_save;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_crop_save()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var succ = parseInt(xmlDoc.getElementsByTagName("crop")[0].childNodes[0].nodeValue);
		if (succ != 1)	{ alert("Your crop settings could not be saved. Please try again..."); }
	}
}

// Create Album
function req_create_album()
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	var url = "/scr/ajax/random.php?ln=12&key="+Math.random();	
	xmlHttp.onreadystatechange = stateChng_create_album;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_create_album()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var rand = xmlDoc.getElementsByTagName("random")[0].childNodes[0].nodeValue;
		var val_name =  document.getElementById('create_album_name').value;
		req_value_list(1,'album',rand,val_name);
	}
}

// Manipulate Value Table
function req_value_list(action,tp,vl,nm)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	if ((action == 1) || (action == 2))
	{	var url = 	"/scr/ajax/value_lists.php?do="+action+"&tp="+escape(tp)
					+"&vl="+escape(vl)+"&nm="+escape(nm)+"&key="+Math.random();
		
		xmlHttp.onreadystatechange = stateChng_value_list;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
}

function stateChng_value_list()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var succ = parseInt(xmlDoc.getElementsByTagName("rate")[0].childNodes[0].nodeValue);
		if (succ == 0)	{ alert("The change could not be made. Please try again..."); }
		else { alert("The requested change was successfully implemented."); }		
	}
}







function req_albm_itm(url)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	xmlHttp.onreadystatechange = stateChng_albm_itm;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_albm_itm()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;

		var action = xmlDoc.getElementsByTagName("do")[0].childNodes[0].nodeValue;	
		var nmbr = xmlDoc.getElementsByTagName("nmbr")[0].childNodes[0].nodeValue;
		var succ = xmlDoc.getElementsByTagName("succ")[0].childNodes[0].nodeValue;
		var fail = xmlDoc.getElementsByTagName("fail")[0].childNodes[0].nodeValue;
		var deja = xmlDoc.getElementsByTagName("deja")[0].childNodes[0].nodeValue;
				
		var albm = document.getElementById('albm_slct').options[document.getElementById('albm_slct').selectedIndex].text;

		if (action == 1)
		{	if (deja != 0)
			{ alert("The photos have been added. "+deja+" of the "+nmbr+" selected photo(s) were already in album '"+albm+"'."); }
			else if (fail == 0)
			{ alert("The "+nmbr+" selected photo(s) have been added to the album '"+albm+"'."); }
			
			for (i = 0; i < nmbr_visible; i = i + 1)
			{	if (document.getElementById('slct_'+i).checked == true)
				{ document.getElementById('in_alb_'+i).style.visibility = 'visible'; }
			}
		}
		else if (action == 2)
		{	if (succ == nmbr) { location.reload(); }
			
		}
	}
}


function gather_checked(action,id,info)
{
	var nmbr_sel = 0; var str = "";
//	alert(nmbr_visible);
	for (i = 0; i < nmbr_visible; i = i + 1)
	{	if (document.getElementById('slct_'+i).checked == true)
		{ str += "-"+document.getElementById('slct_'+i).value; nmbr_sel = nmbr_sel + 1; }
	}
	var send_str = nmbr_sel+str;
	
	if (nmbr_sel == 0) { alert("No photos have been selected. Please select one or more photos below and try again."); }
	else if ((action == 'alb_add') || (action == 'alb_rem'))
	{
		if (action == 'alb_add') { var alb_do = 1; var alb_id = document.getElementById('val_albm_slct').value; }
		else { var alb_do = 2; var alb_id = document.getElementById('val_albm_curr').value; }
		
		var url = "/scr/ajax/album.php?do=" + alb_do + "&id=" + id + "&send="+send_str + "&alb=" + alb_id;	

		req_albm_itm(url);

	}
	else if (action == 'batch_apply')
	{	
		var url = "/scr/ajax/batch_apply.php?fld=" + info
				+ "&val=" + document.getElementById('batch_'+info).value
				+ "&id=" + id + "&send="+send_str;
//		window.open(url);
		req_batch_apply(url);
	}
}

function req_batch_apply(url)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
		
	xmlHttp.onreadystatechange = stateChng_batch_apply;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	
}

function stateChng_batch_apply()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
	
		var field = xmlDoc.getElementsByTagName("batch")[0].getAttribute("field");
		var rtrn_value = xmlDoc.getElementsByTagName("batch")[0].getAttribute("value");
		var nmbr = xmlDoc.getElementsByTagName("batch")[0].getAttribute("nmbr");
		
		for (i = 0; i < nmbr_visible; i++) { for (n = 0; n < xmlDoc.getElementsByTagName("rtrn").length; n++)
		{	if ( ( document.getElementById('val_usr_'+i).value == xmlDoc.getElementsByTagName("rtrn")[n].getAttribute("usr") ) && ( document.getElementById('val_wh_'+i).value == xmlDoc.getElementsByTagName("rtrn")[n].getAttribute("wh") ) )
			{
				if (field == 'rating')
				{	document.getElementById('rate_'+i).selectedIndex = rtrn_value;
					document.getElementById('cell_'+i).style.backgroundColor = '#'+rate_clr_flat[document.getElementById('rate_'+i).options[document.getElementById('rate_'+i).selectedIndex].value];
				}
				if (field == 'status')
				{	document.getElementById('status_'+i).selectedIndex = rtrn_value;
					if (rtrn_value != 0) 	{ document.getElementById('status_advanced_'+i).style.visibility = 'visible'; }
					else 					{ document.getElementById('status_advanced_'+i).style.visibility = 'hidden'; }					
				}				
				
			}		
		}}
		
	}
}



function download_full_album(alb)
{
	var r = confirm( 'If you click OK, your browser will open a separate window'
					+' and download a zipped file of all the photos in the album you are currently viewing.'
					+'\n\nPlease be patient, as it may take several moments (or even several minutes)'
					+' to generate the batch file...');
	if (r == true) { window.open('/scr/php/download.php?alb='+alb); }
	
}


// Original is High Resolution
function req_orig_is_hires(ident)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	if (document.getElementById('orig_hires_'+ident).checked == true) { var action = 2; }
	else { var action = 1; }
		
	var url = 	"/scr/ajax/orig_is_hires.php"
				+"?usr="+document.getElementById('val_usr_'+ident).value
				+"&wh="+document.getElementById('val_wh_'+ident).value
				+"&do="+action+"&key="+Math.random();

//	window.open(url);
				
	xmlHttp.onreadystatechange = stateChng_orig_is_hires;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_orig_is_hires()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var succ = parseInt(xmlDoc.getElementsByTagName("done")[0].childNodes[0].nodeValue);
		var val = parseInt(xmlDoc.getElementsByTagName("value")[0].childNodes[0].nodeValue);
		
		if (succ == 1)
		{
			for (i = 0; i < nmbr_visible; i = i + 1)
				{	if ( 	(document.getElementById('val_usr_'+i).value == xmlDoc.getElementsByTagName("usr")[0].childNodes[0].nodeValue)
						&&	(document.getElementById('val_wh_'+i).value == xmlDoc.getElementsByTagName("time")[0].childNodes[0].nodeValue)
						)
					{ var ident = i; }
				}
//			alert(document.getElementById('have_hires_'+ident).style.visibility);
			if (val == '1234567890') 		{ document.getElementById('have_hires_'+ident).style.visibility = 'visible'; }
			else if (val == '1111111111')	{ document.getElementById('have_hires_'+ident).style.visibility = 'hidden'; }
			else 							{ document.getElementById('have_hires_'+ident).style.visibility = 'visible'; }
		}
		else { alert("That setting could not be saved. Please try again..."); }
		
	}
}

//Batch update captions
function req_cptn_update(url)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
//	alert(url);
	
	xmlHttp.onreadystatechange = stateChng_cptn_update;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_cptn_update()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		var xml_cptn = xmlDoc.getElementsByTagName("cptn");
		for (var i = 0; i <= xml_cptn.length; i++)
		{	if (parseInt(xml_cptn[i].getAttribute("success")) == 1)
			{	for (n = 0; n < nmbr_visible; n++)
				{	if (	(xml_cptn[i].getAttribute("usr") == document.getElementById('val_usr_'+n).value)
						&&	(xml_cptn[i].getAttribute("time") == document.getElementById('val_wh_'+n).value)	)
					{
				//		alert(xml_cptn[i].childNodes[0].nodeValue);
					
					}
				}
			}
		}
	}
}

//Create Caption Version
function req_cptn_vers(ident)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	var url ="/scr/ajax/cptn_version.php"
				+"?usr="+document.getElementById('val_usr_'+ident).value
				+"&wh="+document.getElementById('val_wh_'+ident).value
				+"&tag="+document.getElementById('save_vers_tag_'+ident).value
				;

//	window.open(url);
	
	xmlHttp.onreadystatechange = stateChng_cptn_vers;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_cptn_vers()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;

		for (n = 0; n < nmbr_visible; n++)
		{	if ( 	(document.getElementById('val_usr_'+n).value == xmlDoc.getElementsByTagName("user")[0].childNodes[0].nodeValue)
				&&	(document.getElementById('val_wh_'+n).value == xmlDoc.getElementsByTagName("time")[0].childNodes[0].nodeValue)
				)
			{
				document.getElementById('text_cptn_'+n).value = reclean_xml(xmlDoc.getElementsByTagName("cptn")[0].childNodes[0].nodeValue);

				var vers_menu = document.createElement('option');
				vers_menu.text = xmlDoc.getElementsByTagName("last_tag")[0].childNodes[0].nodeValue;
				vers_menu.value = xmlDoc.getElementsByTagName("last_time")[0].childNodes[0].nodeValue;
				document.getElementById('vers_cptn_'+n).add(vers_menu, null);
				
				document.getElementById('save_vers_tag_'+n).value = '';
				
//				alert(xmlDoc.getElementsByTagName("last_tag")[0].childNodes[0].nodeValue);				
			}
		}
	}
}

//Create Caption Version
function req_cptn_show_vers(ident)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	var url ="/scr/ajax/cptn_return.php"
				+"?usr="+document.getElementById('val_usr_'+ident).value
				+"&wh="+document.getElementById('val_wh_'+ident).value
				+"&vers="+document.getElementById('vers_cptn_'+ident).options[document.getElementById('vers_cptn_'+ident).selectedIndex].value
				;

//	window.open(url);
	
	xmlHttp.onreadystatechange = stateChng_cptn_show_vers;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_cptn_show_vers()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;

		for (n = 0; n < nmbr_visible; n++)
		{	if ( 	(document.getElementById('val_usr_'+n).value == xmlDoc.getElementsByTagName("user")[0].childNodes[0].nodeValue)
				&&	(document.getElementById('val_wh_'+n).value == xmlDoc.getElementsByTagName("time")[0].childNodes[0].nodeValue)
				)
			{
				document.getElementById('text_cptn_'+n).value = reclean_xml(xmlDoc.getElementsByTagName("cptn")[0].childNodes[0].nodeValue);
				document.getElementById('vers_cptn_'+n).selectedIndex = 0;
			}
		}
	}
}

//Batch update captions
function req_note_update(url)
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
//	document.getElementById('text_note_0').value = url;
	
	xmlHttp.onreadystatechange = stateChng_note_update;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_note_update()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
//		alert(xmlDoc.getElementsByTagName("notes")[0].childNodes[0].nodeValue);
//		var xml_cptn = xmlDoc.getElementsByTagName("cptn");
//		for (var i = 0; i <= xml_cptn.length; i++)
//		{	if (parseInt(xml_cptn[i].getAttribute("success")) == 1)
//			{	for (n = 0; n < nmbr_visible; n++)
//				{	if (	(xml_cptn[i].getAttribute("usr") == document.getElementById('val_usr_'+n).value)
//						&&	(xml_cptn[i].getAttribute("time") == document.getElementById('val_wh_'+n).value)	)
//					{
//				//		alert(xml_cptn[i].childNodes[0].nodeValue);
//					
//					}
//				}
//			}
//		}
	}
}