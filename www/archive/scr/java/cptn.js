
function check_changed()
{	
	var sendstr_cptn = "";
	var changed_cptn = "x-";
	
	var sendstr_note = "";
	var changed_note = "x-";		
	
	
	
	for (i = 0; i < nmbr_visible; i = i + 1)
	{
		if (document.getElementById('text_cptn_'+i).value != val_text_cptn[i])
		{	val_text_cptn[i] = document.getElementById('text_cptn_'+i).value;
			sendstr_cptn += "&cp_"+i+"="+document.getElementById('val_usr_'+i).value+"."+document.getElementById('val_wh_'+i).value+"_"+escape(val_text_cptn[i]);
			changed_cptn += "cp_"+i+"-";
		}

		
		
		if (document.getElementById('text_note_'+i).value != val_text_note[i])
		{	val_text_note[i] = document.getElementById('text_note_'+i).value;
			sendstr_note += "&nt_"+i+"="+document.getElementById('val_usr_'+i).value+"."+document.getElementById('val_wh_'+i).value+"_"+escape(val_text_note[i]);
			changed_note += "nt_"+i+"-";
			
		}
	}
	
	if (sendstr_cptn != "")
	{	
		var url_cptn = "/scr/ajax/cptn_update.php?ch=" + changed_cptn + sendstr_cptn;
		req_cptn_update(url_cptn)
	}
	
	if (sendstr_note != "")
	{	
		var url_note = "/scr/ajax/notes_update.php?ch=" + changed_note + sendstr_note;
		req_note_update(url_note)
	}	
	
	var t = setTimeout("check_changed();", 2500);
}

