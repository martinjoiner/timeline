<?php 

if( !file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.inc.php") ){
	print "Error: You have not created config.inc.php in the site root. See config.inc.php.sample for instructions.";
	exit;
}

include( $_SERVER['DOCUMENT_ROOT'] . "/config.inc.php");

try {
	$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_database . ';charset=utf8', $db_username, $db_password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
