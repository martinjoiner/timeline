<!doctype html>
 
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Timeline</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/main.css" />
    <script src="js/timeline.js"></script>
</head>
<body>
	<h1>Timeline</h1>

	<label for="scale">Scale</label><input type="number" id="scale" name="scale" value="26">
	<label for="offset">Offset</label><input type="number" id="offset" name="offset" value="82">

	<div class="newyear" data-start="631152000">1990</div>
	<div class="newyear" data-start="946684800">2000</div>
	<div class="newyear" data-start="1104537600">2005</div>

	<div class="newyear" data-start="1230768000">2009</div>
	<div class="newyear" data-start="1262304000">2010</div>
	<div class="newyear" data-start="1293840000">2011</div>
	<div class="newyear" data-start="1325376000">2012</div>

	<?php 

	include("db_connect.inc.php"); 
	$q = mysql_query("	SELECT `category`.name AS category_name, event.`name`, event.`start`, event.`end`, event.colour
						FROM event
						LEFT JOIN `category` ON event.category_id = category.id
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
			print '<div class="resizable ui-widget-content cat'.$counter++.'" data-height="90">';
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

	<div>
		<input type="date">
	</div>
		
</body>
</html>