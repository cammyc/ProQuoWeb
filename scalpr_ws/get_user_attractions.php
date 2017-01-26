<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$currentDate = $_POST['currentDate'];

	

		$mysqli = getDB();

		echo json_encode(getUserAttractions($mysqli, $userID, $currentDate));

		$mysqli->close();

	
?>