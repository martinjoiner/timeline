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

	<label for="scale">Scale</label><input type="number" id="scale" name="scale" value="26">
	<label for="offset">Offset</label><input type="number" id="offset" name="offset" value="82">

	<div class="newyear" data-start="631152000">1990</div>
	<div class="newyear" data-start="946684800">2000</div>
	<div class="newyear" data-start="978307200">2001</div>
	<div class="newyear" data-start="1009843200">2002</div>
	<div class="newyear" data-start="1041379200">2003</div>
	<div class="newyear" data-start="1072915200">2004</div>
	<div class="newyear" data-start="1104537600">2005</div>
	<div class="newyear" data-start="1136073600">2006</div>
	<div class="newyear" data-start="1167609600">2007</div>
	<div class="newyear" data-start="1199145600">2008</div>
	<div class="newyear" data-start="1230768000">2009</div>
	<div class="newyear" data-start="1262304000">2010</div>
	<div class="newyear" data-start="1293840000">2011</div>
	<div class="newyear" data-start="1325376000">2012</div>

	<?php 

	include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php"); 
	$q = mysql_query("	SELECT `category`.name AS category_name,`category`.height, event.`name`, event.`start`, event.`end`, event.colour
						FROM event
						LEFT JOIN `category` ON event.category_id = category.id
						WHERE `category`.user_id = ".$_SESSION['user_id']." AND event.user_id = ".$_SESSION['user_id']."
						ORDER BY `category`.`id`
					");

	$currentcat = "";
	$counter = 0;
	while($r = mysql_fetch_array($q)){
		if($currentcat != $r["category_name"]){
			if($currentcat != ""){
				print '</div>';
			}
			$currentcat = $r["category_name"];
			print '<div class="resizable ui-widget-content cat'.$counter++.'" data-height="'.$r["height"].'">';
			print '<h2>'.$r["category_name"].'</h2>';
		}
		print '<div class="element" data-start="'.$r["start"].'" data-end="'.$r["end"].'" style="background-color: #'.$r["colour"].';">
				<div class="start date">'.date("M-d-Y",$r["start"]).'</div>
				<div class="end date">'.date("M-d-Y",$r["end"]).'</div>
				<h3>'.$r["name"].'</h3>
			</div>';
	}
	print '</div>';
	?>

<div class="addwrap">
	<i>+</i>
</div>

<div class="eventinputwrap">
	<div class="eventinput">
		<i>X</i>
		<h2>Add event</h2>
		<label>Name</label> <input type="name"><br>
		<label for="username">Colour</label><input type="color" name="colour" maxlength="30" /><br>
		<label>Start</label> <input type="date" name="startdate"><br>
		<label>End</label> <input type="date" name="enddate"><br>
		<input type="button" value="Add">
	</div>
</div>

<script src="/js/timeline.js"></script>
	
</body>
</html>
