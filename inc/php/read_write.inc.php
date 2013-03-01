<?php

function read_file($wh,$creds,$path)
{
	if ($wh == "s3")
	{	require_once("../../inc/php/s3.inc.php");
		$s3 = new S3($creds["s3"][0],$creds["s3"][1]);
		$bucket = substr($path,0,strpos($path,"/"));
		$file = substr($path,1+strpos($path,"/"));
		$obj = $s3->getObject($bucket, $file);
		return $obj->body;
	}
}

function write_file($wh,$creds,$src,$dest)
{
	if ($wh == "s3")
	{	require_once("../../inc/php/s3.inc.php");
		$s3 = new S3($creds["s3"][0],$creds["s3"][1]);
		$bucket = substr($dest,0,strpos($dest,"/"));
		$file = substr($dest,1+strpos($dest,"/"));
		
		if (file_exists($src)) { $obj = $s3->putObject($s3->inputResource(fopen($src, 'rb'), filesize($src)), $bucket, $file, $acl = S3::ACL_PUBLIC_READ); }
		else { $obj = $s3->putObjectString($src, $bucket, $file, $acl = S3::ACL_PUBLIC_READ); }
//		$obj = $s3->putObjectFile($src, $bucket, $file, $acl = S3::ACL_PUBLIC_READ);
			
//		$obj = $s3->putObject($s3->inputResource(fopen($src, 'rb'), filesize($src)), $bucket, $file, $acl = S3::ACL_PUBLIC_READ);
		
		return $obj;
	}
	
}

?>