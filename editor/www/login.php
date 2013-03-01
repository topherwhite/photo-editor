<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<title>wint.erti.me - login</title> 
<link href="/css/univ.css" rel="stylesheet" type="text/css" />
<script src="/js/all.js" type="text/javascript"></script>
</head>
<body onLoad=""> 
	<div class="container" id="container">
		
		<table class="login" id="login_table">
		
		<tr><td class="pad"></td><td colspan="2" class="login_header"><img src="/img/stat/logo/wint.erti.me.png" style="" /><img style="position:relative;top:4px;" src="/img/dyn/txt?str=24_190_222222_ffffff_futura_wint.erti.me" /></td><td class="pad"></td></tr>
		
		<tr><td class="pad"></td><td class="login_label"><label id="label_login_email" for="login_email">login e-mail:</label></td><td class="login_input"><input type="text" class="text_input" id="login_email" size="25" onKeyPress="return js_on_enter(event,'req_login();')" /></td><td class="pad"></td></tr> 
		<tr><td class="pad"></td><td class="login_label"><label id="label_login_password" for="login_password">password:</label></td><td class="login_input"><input type="password" class="text_input" id="login_password" size="25" onKeyPress="return js_on_enter(event,'req_login();')" /></td><td class="pad"></td></tr> 
	 	
		<tr><td colspan="4" id="login_msg"></td></tr>
		
		<tr><td class="pad"></td><td colspan="2"><input type="button" id="login_submit" value="login &gt;" onClick="req_login();" /></td><td class="pad"></td></tr> 
	 
		<tr><td colspan="4" class="footer">If you need assistance, please send an e-mail to <a href="mailto:webmaster@iter.org">webmaster@iter.org</a>.</td></tr> 
			
		</table>
		
		<input type="hidden" id="login_pageload" value="<?php echo mktime(); ?>" />
		<input type="hidden" id="action" value="login" />
		
	</div>
</body> 
</html>