<?php

function xml_clean_simple($instr)
{
	$orig = array(	"&",		"<",		">",		"\""		//strict
				//	,"'"
					,"\n"								//extra
					);
	$repl = array(	"&amp;",	"&lt;",		"&gt;",		"&quot;"	//strict
				//	,"&#39;"
					,"¶"											//extra
					);
	return str_replace($orig,$repl,$instr);
}

?>