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

// Query database using server session user id
$qryEventCode = $db->prepare("	SELECT 	`category`.`id` AS categoryID, 
										`category`.`name` AS categoryName,
										`category`.`hue` AS categoryHue,
										`event`.`id` as eventID, 
										`event`.`name` as eventName, 
										`event`.`startdate`, 
										`event`.`enddate`
								FROM `event`
								LEFT JOIN `category` ON `event`.`category_id` = `category`.`id`
								WHERE `category`.user_id = :userID 
								AND `event`.`user_id` = :userID 
								ORDER BY `category`.`id`, `event`.`startdate` 
							"); 

$qryEventCode->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
$qryEventCode->execute();
$r = $qryEventCode->fetchAll(PDO::FETCH_ASSOC);
$qryEventCode->closeCursor();


// Loop through the flat database result creating a nested tree structure of categories and events
$curCat = 0;
$cntCats = -1;
$cntEventsInCat = 0;
$earliestStart = null;
$latestEnd = null;

$skvReturn['success'] = true;
$skvReturn['cntEvents'] = sizeof($r);

if( sizeof($r) ){

	foreach( $r as $thisR ){

		if( $thisR['categoryID'] != $curCat ){
			$cntEventsInCat = 0;
			$cntCats++;
			$curCat = $thisR['categoryID'];
		} else {
			$cntEventsInCat++;
		}
		$skvReturn['arrCategories'][ $cntCats ]['id'] = intval($thisR['categoryID']);
		$skvReturn['arrCategories'][ $cntCats ]['name'] = $thisR['categoryName'];
		$skvReturn['arrCategories'][ $cntCats ]['hue'] = $thisR['categoryHue'];
		$skvReturn['arrCategories'][ $cntCats ]['arrEvents'][$cntEventsInCat]['id'] = $thisR['eventID'];
		$skvReturn['arrCategories'][ $cntCats ]['arrEvents'][$cntEventsInCat]['name'] = $thisR['eventName'];
		$skvReturn['arrCategories'][ $cntCats ]['arrEvents'][$cntEventsInCat]['startDate'] = $thisR['startdate'];
		$skvReturn['arrCategories'][ $cntCats ]['arrEvents'][$cntEventsInCat]['endDate'] = $thisR['enddate'];

		// A null end date means the period is current
		if( is_null($thisR['enddate']) ){
			$latestEnd = date('Y-n-j');
		}

		$tempStart = new DateTime( $thisR['startdate'] );
		if( is_null($earliestStart) || $tempStart < $earliestStart ){
			$earliestStart = $tempStart;
		}

		$tempEnd = new DateTime( $thisR['enddate'] );
		if( is_null($tempEnd) || $tempEnd > $latestEnd ){
			$latestEnd = $tempEnd;
		}

	}

	// Add meta data for overall life
	$skvReturn['lifeStart'] = $earliestStart->format( 'Y-n-j' );
	$skvReturn['lifeEnd'] = $latestEnd->format( 'Y-n-j' );
	$diffParts = $earliestStart->diff( $latestEnd );
	$skvReturn['lifeDays'] = $diffParts->days;

}

// Print structure in JSON format
echo json_encode($skvReturn);
