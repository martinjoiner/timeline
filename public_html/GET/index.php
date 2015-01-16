<?php

session_start();

header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php"); 

// Query database using server session user id
$qryEventCode = $db->prepare("	SELECT 	`category`.`id` AS categoryID, 
										`category`.`name` AS categoryName,
										`category`.`height` AS categoryHeight,
										`event`.`name` as eventName, 
										`event`.`startdate`, 
										`event`.`enddate`, 
										`event`.`colhex` 
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
foreach( $r as $thisR ){

	if( $thisR['categoryID'] != $curCat ){
		$cntEventsInCat = 0;
		$cntCats++;
		$curCat = $thisR['categoryID'];
	} else {
		$cntEventsInCat++;
	}
	$skvReturn['skvCategories'][ $cntCats ]['id'] = intval($thisR['categoryID']);
	$skvReturn['skvCategories'][ $cntCats ]['name'] = $thisR['categoryName'];
	$skvReturn['skvCategories'][ $cntCats ]['skvEvents'][$cntEventsInCat]['name'] = $thisR['eventName'];
	$skvReturn['skvCategories'][ $cntCats ]['skvEvents'][$cntEventsInCat]['startDate'] = $thisR['startdate'];
	$skvReturn['skvCategories'][ $cntCats ]['skvEvents'][$cntEventsInCat]['endDate'] = $thisR['enddate'];
	$skvReturn['skvCategories'][ $cntCats ]['skvEvents'][$cntEventsInCat]['colhex'] = $thisR['colhex'];

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
$skvReturn['lifeDays'] = $earliestStart->diff( $latestEnd );

// Print structure in JSON format
echo json_encode($skvReturn);
