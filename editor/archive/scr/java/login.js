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



function req_user_login()
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	var usr = document.getElementById('user_login').options[document.getElementById('user_login').selectedIndex].value;
	
	url = "/scr/ajax/login.php?usr="+ usr +"&key=" + Math.random();
	
	xmlHttp.onreadystatechange = stateChng_user_login;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChng_user_login()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		success = parseInt(xmlDoc.getElementsByTagName("login")[0].childNodes[0].nodeValue);
		
		if (success == 1) { location = 'http://editor.againstallodds.com/'; }
		else { alert('There was an error and you could not be logged in.'); }
	}
}