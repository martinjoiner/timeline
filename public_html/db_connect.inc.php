<?php 

include( $_SERVER['DOCUMENT_ROOT'] . "/config.inc.php");
$dbh = mysql_connect ("localhost", $db_username, $db_password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($db_database); 
