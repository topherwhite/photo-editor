

function popup(action,ident,wh)
{
	if (action == 1)
	{	
		if ( (wh == 'view') && (document.getElementById('popup_img_'+wh+'_'+ident).src.substring(50) == 'gif') )
		{	
			for (i = 0; i < nmbr_visible; i++) { popup(0,i,wh); }
			
			document.getElementById('popup_img_'+wh+'_'+ident).src = path_img_srvr+'img/dynamic/view.'+document.getElementById('val_usr_'+ident).value+'.'+document.getElementById('val_wh_'+ident).value+'.jpg';
			
		}
		document.getElementById('popup_'+wh+'_'+ident).style.visibility = 'visible';
		
	}
	
	else if (action == 0)
	{
		
		document.getElementById('popup_'+wh+'_'+ident).style.visibility = 'hidden';
		
	}
	
		
}
