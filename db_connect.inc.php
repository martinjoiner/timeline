<?php 

$dbh = mysql_connect ("localhost", "timeline", "unsecure") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("timeline"); 

?>