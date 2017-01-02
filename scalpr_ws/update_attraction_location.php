<?php
	include_once("databasehelper.php");

	$attraction = new attraction();
	$attraction->creatorID = $_POST['creatorID'];
	$attraction->attractionID = $_POST['attractionID'];
	$attraction->lat = $_POST['lat'];
	$attraction->lon = $_POST['lon'];

	// Required field names
	$required = array('creatorID', 'attractionID', 'lat', 'lon');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	    $error = true;
	  }
	}

	if ($error) {
	  echo 0;
	} else {

		$mysqli = getDB();

		echo updateAttractionLocation($mysqli,$attraction);

		$mysqli->close();
	}
?>