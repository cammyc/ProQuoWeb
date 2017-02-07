<?php
	include_once("databasehelper.php");
	$creatorID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$attraction = new attraction();
	$attraction->creatorID = $creatorID;
	$attraction->attractionID = $_POST['attractionID'];
	$attraction->lat = $_POST['lat'];
	$attraction->lon = $_POST['lon'];



		$mysqli = getDB();

		echo updateAttractionLocation($mysqli,$attraction);

		$mysqli->close();
	
?>