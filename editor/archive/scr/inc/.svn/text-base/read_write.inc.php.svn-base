<?php

function read_file($wh,$path)
{
	if ($wh == "s3")
	{	require_once("/liz/www/archive/scr/inc/s3.inc.php");
		$s3 = new S3("AKIAI5ND7YMXBZTJD2RQ", "D1esSSfOV+EY9H4Y4U2c23/ekvc8jVRT0gJpQy0W");
		$bucket = substr($path,0,strpos($path,"/"));
		$file = substr($path,1+strpos($path,"/"));
		$obj = $s3->getObject($bucket, $file);
		return $obj->body;
	}
}

function write_file($wh,$src,$dest)
{
	if ($wh == "s3")
	{	require_once("/liz/www/archive/scr/inc/s3.inc.php");
		$s3 = new S3("AKIAI5ND7YMXBZTJD2RQ", "D1esSSfOV+EY9H4Y4U2c23/ekvc8jVRT0gJpQy0W");
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