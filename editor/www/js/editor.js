


function label_change(id,state)
{	
	if (document.getElementById(id) != null)
	{	if (state == 'red')
		{	document.getElementById('label_'+id).style.color = 'red';
			document.getElementById('label_'+id).style.fontWeight = 'bold';
			document.getElementById(id).style.borderColor = 'red';
		}
		else if (state == 'black')
		{	document.getElementById('label_'+id).style.color = 'black';
			document.getElementById('label_'+id).style.fontWeight = 'normal';
			document.getElementById(id).style.borderColor = 'gray';
		}
	}
}

function req_login()
{	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) { alert ("Your browser does not support this feature."); return; }
	
	
	var proceed = '1';
	
	if ((document.getElementById('login_email').value != null) && (is_email(document.getElementById('login_email').value))) { label_change('login_email','black'); }
	else { label_change('login_email','red'); proceed = 0; }

	if ((document.getElementById('login_password').value != null) && (document.getElementById('login_password').value != '')) { label_change('login_password','black'); }
	else { label_change('login_password','red'); proceed = 0; }	


	var url = "/ws/user?key="+Math.random();
	
	var m = new Matrix();
	m.r[0] = new Array(	'action'
						,'login_email'
						,'login_password'
						,'login_pageload');
	m.r[1] = new Array(	'login'
						,document.getElementById('login_email').value
						,document.getElementById('login_password').value
						,document.getElementById('login_pageload').value);
							
	if (proceed == 1)
	{	document.getElementById('login_submit').style.visibility = 'hidden';
		document.getElementById('login_msg').innerHTML = 'logging in...';
		var rtrn_func = "stateChng_login"; xmlHttp.onreadystatechange = eval(rtrn_func);
		var bnd_str = "*-YWC-*"; var bnd = "--"+bnd_str; var req_bdy = bnd;
		for (i = 0; i < m.r[0].length; i = i+1) { req_bdy += "\nContent-Disposition: form-data; name=\""+m.r[0][i]+"\""+"\n\n"+m.r[1][i]+"\n"+bnd; }					
		xmlHttp.open("POST",url,true); xmlHttp.setRequestHeader("Content-type","multipart/form-data; boundary=\""+bnd_str+"\"");
//		xmlHttp.setRequestHeader("Content-length",req_bdy.length); xmlHttp.setRequestHeader("Connection","close");
		xmlHttp.send(req_bdy);
	}
	else
	{	document.getElementById('login_submit').style.visibility = 'visible';
		document.getElementById('login_msg').innerHTML = 'please correct/complete the field(s) marked in red above...';
		var t = setTimeout("document.getElementById('login_msg').innerHTML='';",3000);
	}	
	
}

function stateChng_login()
{	if (xmlHttp.readyState == 4)
	{	var xmlDoc=xmlHttp.responseXML.documentElement;
		
		var err = xmlDoc.getElementsByTagName('login')[0].getAttribute('error');
		
		var msg = new Array();
		msg['badpass'] = 'the submitted password is incorrect...';
		msg['bademail'] = 'the submitted e-mail has no account...';
		msg['timeout'] = 'too much time has passed. please log in again...';
		
		if (err != 'none')
		{	document.getElementById('login_submit').style.visibility = 'visible';
			document.getElementById('login_msg').innerHTML = msg[err];
		}
		else
		{	
			document.getElementById('container').innerHTML =
						'<table class="login" id="login_table">'
						+'<tr><td class="pad"></td><td colspan="2"><img src="/img/dyn/txt?str=24_190_000000_ffffff_futura_wint.erti.me" /></td><td class="pad"></td></tr>'
						+'<tr><td class="pad"></td><td colspan="2">'
						+'<a href=""><div style="width:200px;height:50px;border:solid 1px gray;margin-right:auto;margin-left:auto;">hello</div></a>'
						+'</td><td class="pad"></td></tr>'
						+'</table>'
						;
		}
	}
}

function upload_file(action,location,rep)
{	
	var check_interval = 1000;
	
	if	(	(action == 'sub')
		&&	(document.getElementById('upl_file_'+rep) != null)
		&&	(document.getElementById('upl_file_'+rep).value != '')
		)
	{	document.getElementById('upl_form_'+rep).submit();
		document.getElementById('upl_form_'+rep).style.visibility = 'hidden';
		document.getElementById('upl_file_'+rep).style.visibility = 'hidden';
		document.getElementById('upl_img_'+rep).style.visibility = 'visible';
		document.getElementById('upl_msg_'+rep).innerHTML = "Uploading...";
		document.getElementById('upl_msg_'+rep).style.visibility = 'visible';
		
		draw_upload_box(location,parseInt(rep)+1);
		
		var t = setTimeout("upload_file('check','"+location+"','"+rep+"');",check_interval);
	}
	else if	(	(action == 'check')
		&&	(document.getElementById('upl_targ_'+rep) != null)
		)
	{	if (document.getElementById('upl_targ_'+rep).contentWindow.document.getElementById('file_name_'+rep) != null)
		{	
			var msg = 'Done: '
					+document.getElementById('upl_targ_'+rep).contentWindow.document.getElementById('file_name_'+rep).value.substring(0,20)
					+' ('+(Math.round(100*document.getElementById('upl_targ_'+rep).contentWindow.document.getElementById('file_size_'+rep).value/1024/1024)/100)+' MB)';
			document.getElementById('upl_img_'+rep).src = '/img/stat/icon/checkbox.png';
			document.getElementById('upl_msg_'+rep).innerHTML = msg;
		}
		else { var t = setTimeout("upload_file('check','"+location+"','"+rep+"');",check_interval); }
	}
}


function draw_upload_box(location,rep)
{
	var outer_container = document.getElementById(location);
	var div_cont = document.createElement('div');
	div_cont.setAttribute('id','upl_cont_'+rep);
	div_cont.setAttribute('class','upl_cont');
	div_cont.innerHTML = ""
			+"<div class=\"upl_cntr\">"+(parseInt(rep)+1)+")</div>"
			+"<form id=\"upl_form_"+rep+"\" target=\"upl_targ_"+rep+"\" action=\"/ws/upload\" enctype=\"multipart/form-data\" method=\"post\" class=\"upl_form\">"
			+"<input id=\"upl_file_"+rep+"\" name=\"upl_file_"+rep+"\" type=\"file\" onChange=\"upload_file('sub','"+location+"','"+rep+"');\" class=\"upl_file\" />"
			+"<input id=\"upl_rep\" name=\"upl_rep\" type=\"hidden\" value=\""+rep+"\" />"
			+"</form><iframe frameborder=\""+rep+"\" id=\"upl_targ_"+rep+"\" name=\"upl_targ_"+rep+"\" class=\"upl_targ\"></iframe>"
			+"<img id=\"upl_img_"+rep+"\" class=\"upl_img\" src=\"/img/stat/anim/loading.gif\" />"
			+"<span id=\"upl_msg_"+rep+"\" class=\"upl_msg\"></span>";
	outer_container.appendChild(div_cont);
}