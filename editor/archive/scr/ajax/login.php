<?php

header('Content-type: text/xml');

require_once("../inc/var.inc.php");

session_start();
session_set_cookie_params(17280000);

$_SESSION['usr'] = mysqlclean($_GET,"usr",2,$connection);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
	."\n<photoeditor>";
	
if (!empty($_SESSION['usr'])) { echo "<login>1</login>"; }
else { echo "<login>0</login>"; }


echo "</photoeditor>";

?>