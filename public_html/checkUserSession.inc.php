<?php

session_start();

if( !$_SESSION['user_id'] ){
	$skvReturn['success'] = false;
	$skvReturn['error'] = 'No user logged in';
	// Print structure in JSON format
	echo json_encode($skvReturn);
	exit;
}
