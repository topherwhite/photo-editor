#!/usr/bin/php -q
<?php

require_once("/liz/editor/inc/php/vars.inc.php");
require_once("/liz/editor/inc/php/rand.inc.php");
require_once("/liz/editor/inc/php/xml2array.inc.php");
require_once("/liz/editor/inc/php/read_write.inc.php");
require_once("/liz/editor/inc/php/ingest.inc.php");

$timeout = 50;
$ingest_start_time = mktime();

$upld_dirs = array(	"upld_webdav"
					,"upld_vsftpd"
					,"upld_web"
					);

if ($con = mysql_conn($var))
{	foreach($upld_dirs as $upld_dir)
	{
		echo "\nscanning '{$upld_dir}'";
	
		$user_dirs = scandir("{$var['path']['tmp']}/{$upld_dir}");
		foreach($user_dirs as $user_dir)
		{	if ((substr($user_dir,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}"))
			{	$files0 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}");
				foreach($files0 as $file0)
				{	if ((substr($file0,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}"))
					{	process_file_list($file0,$upld_dir,$user_dir,$var,$con);
					}
					elseif ((substr($file0,0,1) != ".") && ($file0 != "-incompatible-files") && ($file0 != "-failed-to-ingest") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}"))
					{	$files1 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}");
						foreach($files1 as $file1)
						{	if ((substr($file1,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}"))
							{	process_file_list("{$file0}/{$file1}",$upld_dir,$user_dir,$var,$con);
							}
							elseif ((substr($file1,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}"))
							{	$files2 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}");
								foreach($files2 as $file2)
								{	if ((substr($file2,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}"))
									{	process_file_list("{$file0}/{$file1}/{$file2}",$upld_dir,$user_dir,$var,$con);
									}
									elseif ((substr($file2,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}"))
									{	$files3 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}");
										foreach($files3 as $file3)
										{	if ((substr($file3,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}"))
											{	process_file_list("{$file0}/{$file1}/{$file2}/{$file3}",$upld_dir,$user_dir,$var,$con);
											}
											elseif ((substr($file3,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}"))
											{	$files4 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}");
												foreach($files4 as $file4)
												{	if ((substr($file4,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}"))
													{	process_file_list("{$file0}/{$file1}/{$file2}/{$file3}/{$file4}",$upld_dir,$user_dir,$var,$con);
													}
													elseif ((substr($file4,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}"))
													{	$files5 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}");
														foreach($files5 as $file5)
														{	if ((substr($file5,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}"))
															{	process_file_list("{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}",$upld_dir,$user_dir,$var,$con);
															}
															elseif ((substr($file5,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}"))
															{	$files6 = scandir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}");
																foreach($files6 as $file6)
																{	if ((substr($file6,0,1) != ".") && !is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}/{$file6}"))
																	{	process_file_list("{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}/{$file6}",$upld_dir,$user_dir,$var,$con);
																	}
																	elseif ((substr($file6,0,1) != ".") && is_dir("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}/{$file1}/{$file2}/{$file3}/{$file4}/{$file5}/{$file6}"))
																	{	echo " > directory descent will stop here...";
																}	}
														}	}
												}	}
										}	}
								}	}
						}	}
						create_incompatible_directory($upld_dir,$user_dir,$var);
						rename("{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/{$file0}","{$var['path']['tmp']}/{$upld_dir}/{$user_dir}/-incompatible-files/{$file0}"); //}
				}	}
		}	}
		echo "\n";
}	}


echo "\n";




?>
