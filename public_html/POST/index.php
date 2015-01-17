<?php

session_start();

header('Content-Type: application/json');

if( !$_SESSION['user_id'] ){
	$skvReturn['success'] = false;
	$skvReturn['error'] = 'No user logged in';
	// Print structure in JSON format
	echo json_encode($skvReturn);
	exit;
}

include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php"); 


if( $_POST['category_id'] == '' ){

	// Insert new category into db
	$qryInsCat = $db->prepare( ' 	INSERT INTO `category` ( `user_id`, `name`, `hue` )
									VALUES ( :userID, :name, :hue )
								' ); 
	$qryInsCat->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
	$qryInsCat->bindValue('name', $_POST['newCategory'], PDO::PARAM_STR);
	$qryInsCat->bindValue('hue', rand(0, 360), PDO::PARAM_INT);
	$qryInsCat->execute();
	$_POST['category_id'] = $db->lastInsertId();
	$qryInsCat->closeCursor();
}


$strSQL = 'INSERT INTO	`event` ( 	`user_id`, 
									`category_id`, 
									`name`, 
									`startdate` ';

if( !$_POST['noEnd'] ){
	$strSQL .= ' , `enddate` ';
}

$strSQL .= ' )
			VALUES ( 	:userID,
						:categoryID, 
						:eventName,
						:startDate ';

if( !$_POST['noEnd'] ){
	$strSQL .= ' , :endDate ';
}

$strSQL .= ' ) ';
									

// Query database using server session user id
$qryInsEvent = $db->prepare( $strSQL ); 

$qryInsEvent->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
$qryInsEvent->bindValue('categoryID', $_POST['category_id'], PDO::PARAM_INT);
$qryInsEvent->bindValue('eventName', $_POST['name'], PDO::PARAM_STR);
$qryInsEvent->bindValue('startDate', $_POST['startdate'], PDO::PARAM_STR);
if( !$_POST['noEnd'] ){
	$qryInsEvent->bindValue('endDate', $_POST['enddate'], PDO::PARAM_STR);
}
$qryInsEvent->execute();
$newID = $db->lastInsertId();
$qryInsEvent->closeCursor();




$skvReturn['success'] = false;
if( $newID ){
	$skvReturn['success'] = true;
	$skvReturn['skvCategory']['id'] = intVal($_POST['category_id']);
	$skvReturn['skvCategory']['name'] = $_POST['newCategory'];
	$skvReturn['skvCategory']['arrEvents'][0]['id'] = intVal($newID);
	$skvReturn['skvCategory']['arrEvents'][0]['name'] = $_POST['name'];
	$skvReturn['skvCategory']['arrEvents'][0]['startDate'] = $_POST['startdate'];
	if( $_POST['enddate'] ){
		$skvReturn['skvCategory']['arrEvents'][0]['endDate'] = $_POST['enddate'];
	} else {
		$skvReturn['skvCategory']['arrEvents'][0]['endDate'] = date('Y-n-j');
	}
}


// Print structure in JSON format
echo json_encode($skvReturn);
