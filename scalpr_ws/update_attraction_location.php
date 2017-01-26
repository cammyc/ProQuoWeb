<?php
	include_once("databasehelper.php");

	$attraction = new attraction();
	$attraction->creatorID = $_POST['creatorID'];
	$attraction->attractionID = $_POST['attractionID'];
	$attraction->lat = $_POST['lat'];
	$attraction->lon = $_POST['lon'];



		$mysqli = getDB();

		echo updateAttractionLocation($mysqli,$attraction);

		$mysqli->close();
	
?>