<?php

include($_SERVER['DOCUMENT_ROOT'] . "/checkUserSession.inc.php");

header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php"); 


// Query database using server session user id
$qryDeleteEvent = $db->prepare( ' 	DELETE FROM `event` 
									WHERE `id` = :eventID 
									AND `user_id` = :userID 
									LIMIT 1
								' ); 

$qryDeleteEvent->bindValue('eventID', $_POST['eventID'], PDO::PARAM_INT);
$qryDeleteEvent->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
$qryDeleteEvent->execute();
$qryDeleteEvent->closeCursor();


// Perform a second query to check the event has been deleted
$qryCheckEvent = $db->prepare( ' 	SELECT COUNT(`id`) as cnt 
									FROM `event` 
									WHERE `id` = :eventID 
									AND `user_id` = :userID 
								' ); 
$qryCheckEvent->bindValue('eventID', $_POST['eventID'], PDO::PARAM_INT);
$qryCheckEvent->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
$qryCheckEvent->execute();
$r = $qryCheckEvent->fetchAll(PDO::FETCH_ASSOC);
$qryCheckEvent->closeCursor();


$skvReturn['success'] = false;
if( $r[0]['cnt'] == 0 ){
	$skvReturn['success'] = true;
	$skvReturn['eventID'] = intVal($_POST['eventID']);
}


// Print structure in JSON format
echo json_encode($skvReturn);
