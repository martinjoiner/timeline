<?php
session_start();
include( $_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");
// If the user has not logged in
if(!isLoggedIn()){
    header('Location: /');
    die();
}
?><!DOCTYPE html> 
<html>
<head>
    <meta charset="utf-8" />
    <title>Timeline</title>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/head_includes.inc.php"); ?>
    <link rel="stylesheet" href="/css/mine.css" />
</head>
<body>
	<div class="header">
		<h1>Timeline</h1>
		<div class="loggedin">Logged in as <?php print $_SESSION['username']; ?><br><a href="/logout.php">Logout</a></div>
	</div>

	<div class="pageControls">

		<div class="controlWrap">
			<label for="zoom">Zoom</label><input type="number" id="zoom" name="zoom" value="0" min="0" max="20" step="1">
		</div>

		<div class="controlWrap">
			<label for="offset">Offset</label><input type="number" id="offset" name="offset" value="0" step="10">
		</div>

		<button class="btnAddEvent">
			<i>+</i>
			<span>Add event</span>
		</button>
	</div>

	<div class="window">

		<div class="lifeBoard">

			<div class="topLifeBoardSpacer"></div>

			<?php

			include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php"); 

			$qryEventCode = $db->prepare("	SELECT `category`.name AS category_name,
													`category`.id as categoryID, 
													`category`.height, 
													`event`.`id`, 
													`event`.`name`, 
													`event`.`startdate`, 
													`event`.`enddate`
											FROM `event`
											LEFT JOIN `category` ON `event`.`category_id` = `category`.`id`
											WHERE `category`.user_id = :userID 
											AND `event`.`user_id` = :userID 
											ORDER BY `category`.`id`
										"); 
			$qryEventCode->bindValue('userID', $_SESSION['user_id'], PDO::PARAM_INT);
			$qryEventCode->execute();
			$r = $qryEventCode->fetchAll(PDO::FETCH_ASSOC);
			$qryEventCode->closeCursor();

			$currentcat = "";
			$counter = 0;
			foreach($r as $thisR){
				if($currentcat != $thisR["category_name"]){
					if($currentcat != ""){
						print '</div>';
					}
					$currentcat = $thisR["category_name"];
					print '<div class="categoryRow resizable ui-widget-content cat'.$counter++.'" data-height="'.$thisR["height"].'" id="c' . $thisR['categoryID'] . '">';
					print '<h2>'.$thisR["category_name"] . '&#133;</h2>';
				}
				print '<div class="element" id="e' . $thisR["id"] . '" data-start="' . $thisR["startdate"] . '" data-end="'.$thisR["enddate"].'" >
						<h3>' . $thisR["name"] . '</h3>
						<span class="start date">'. $thisR["startdate"] .'</span> 
						<span class="end date">'. $thisR["enddate"] . '</span>
					</div>';
			}
			print '</div>';
			?>

		</div><!-- /.lifeBoard -->

	</div><!-- /.window -->

	<div class="eventInputwrap EIWcollapsed">
		<div class="eventInput">
			<i>X</i>
			<h2>Add event</h2>
			<div class="formRow">
				<label for="name">Name</label>
				<input type="text" id="name">
			</div>
			<div class="formRow">
				<label for="category_id">Category</label>
				<select id="category_id"></select><br>
			</div>
			<div class="formRow">
				<label>Start</label>
				<input type="date" name="startdate">
			</div>
			<div class="formRow">
				<label for="enddate">End</label>
				<input type="date" id="enddate" name="enddate">
			</div>
			<div class="formRow">
				<label>&nbsp;</label>
				<input type="button" value="Cancel" id="btnCancelAddEvent">
				<input type="button" value="Add" id="btnSubmitAddEvent">
			</div>
		</div>
	</div>

	<script src="/js/timeline.js"></script>
	
</body>
</html>
