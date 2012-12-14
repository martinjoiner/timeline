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

	<input type="number" id="scale" name="scale" value="10">

	<?php 

	include("db_connect.inc.php"); 
	$q = mysql_query("	SELECT `category`.name AS category_name, event.`name`, event.`start`, event.`end`
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
		print '<div class="element" data-start="'.$r["start"].'" data-end="'.$r["end"].'"><h3>'.$r["name"].'</h3>
			<div class="arrow"></div></div>';
	}
	print '</div>';
	?>
 
	<div class="resizable ui-widget-content cat<?php print $counter++; ?>" data-height="90">
		<div class="element" data-start="0" data-duration="200">
			<div class="start date">12 Nov 2011</div>
			<div class="end date">1 January 2012</div>
			<h3>Job 1</h3>
			<div class="arrow"></div>
		</div>
		<div class="element" data-start="210" data-duration="100">
			<div class="start date">12 Nov 2011</div>
			<div class="end date">1 January 2012</div>
			<h3>Job 2</h3>
			<div class="arrow"></div>
		</div>
		<div class="element" data-start="302" data-duration="200">
			<div class="arrow"></div>
		</div>
		<div class="element" data-start="600" data-duration="200">
			<div class="arrow"></div>
		</div>
	</div>

	<div class="resizable ui-widget-content cat2" data-height="160">
		<div class="element" data-start="10" data-duration="143">
			<div class="start date">12 Nov 2011</div>
			<div class="end date">1 January 2012</div>
			<h3>Girl Number 1</h3>
			<div class="arrow"></div>
		</div>
		<div class="element" data-start="160" data-duration="643">
			<div class="start date">12 Nov 2011</div>
			<div class="end date">1 January 2012</div>
			<h3>Girl Number 2</h3>
			<div class="arrow"></div>
		</div>
		<div class="element" data-start="900" data-duration="180"></div>
		
		<div class="element" data-start="900" data-duration="239"></div>
	</div>

	<div class="resizable ui-widget-content cat3" data-height="120">
		<div class="element" data-start="0" data-duration="14">
			<div class="start date">May 1983</div>
			<div class="end date">July 1983</div>
			<h2>Born in Salisbury</h2>
		</div>
		<div class="element" data-start="14" data-duration="1008">
			<div class="start date">July 1983</div>
			<div class="end date">September 2001</div>
			<h2>Parents house, Taunton</h2>
		</div>
		<div class="element" data-start="1355492098" date-end="1355492174">
			<div class="start date">September 2001</div>
			<div class="end date">June 2002</div>
			<h2>Student house, Plymouth</h2>
		</div>
		<div class="element" data-start="1062" data-duration="40">
			<div class="start date">September 2002</div>
			<div class="end date">June 2003</div>
			<h2>2nd student house, Plymouth</h2>
		</div>
		<div class="element" data-start="1084" data-duration="3">
			<div class="start date">June 2002</div>
			<div class="end date">July 2003</div>
			<h2>Shared house, London</h2>
		</div>
		<div class="element" data-start="900" data-duration="200"></div>
		<div class="element" data-start="1200" data-duration="200"></div>
	</div>

	<div>
		<input type="date">
	</div>

	<footer class="post-info">

	</footer>
		
</body>
</html>