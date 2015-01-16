<?php 

if( !file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.inc.php") ){
	print "Error: You have not created config.inc.php in the site root. See config.inc.php.sample for instructions.";
	exit;
}

include( $_SERVER['DOCUMENT_ROOT'] . "/config.inc.php");
$dbh = mysql_connect ("localhost", $db_username, $db_password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($db_database); 
