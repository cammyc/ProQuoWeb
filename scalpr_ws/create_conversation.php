<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$attractionID = $_POST['attractionID'];
	$buyerID = $_POST['buyerID'];
	$attractionName = $_POST['attractionName'];

	

		$mysqli = getDB();

		echo CreateConversation($mysqli, $attractionID, $buyerID, $attractionName);

		$mysqli->close();

	
?>