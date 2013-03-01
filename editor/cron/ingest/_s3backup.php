#!/usr/bin/php -q
<?php

require_once("/liz/editor/inc/php/vars.inc.php");
require_once("/liz/editor/inc/php/read_write.inc.php");


$files = scandir("{$var['path']['tmp']}/done");

if ($con = mysql_conn($var))
{	foreach($files as $ind=>$val)
	{	if (substr($val,0,1) != ".")
		{	echo "\n{$val}";
			$each = scandir("{$var['path']['tmp']}/done/{$val}");
			foreach($each as $ind_=>$val_)
			{	if (substr($val_,0,1) != ".")
				{	if ( (substr($val_,0,5) == "orig_") || (substr($val_,0,5) == "meta_") )
					{	echo " -> uploading ".substr($val_,0,4);
						write_file(	"s3",$var['creds']
								,"{$var['path']['tmp']}/done/{$val}/{$val_}"
								,"aaoeditor/img/orig/".substr($val,0,2)."/".substr($val_,5));
						echo " (+)";
					}
					elseif (substr($val_,0,5) == "jpeg_")
					{	echo " -> uploading ".substr($val_,0,4);
						write_file(	"s3",$var['creds']
									,"{$var['path']['tmp']}/done/{$val}/{$val_}"
									,"aaoeditor/img/jpeg/".substr($val,0,2)."/".substr($val_,5));
						echo " (+)";
					}
					unlink("{$var['path']['tmp']}/done/{$val}/{$val_}");
			}	}
			rmdir("{$var['path']['tmp']}/done/{$val}");
}	}	}	
mysql_close($con);


echo "\n";


?>
