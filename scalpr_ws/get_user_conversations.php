<?php
	include_once("databasehelper.php");
	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	// $userID = $_POST['userID'];
	//$currentDate = $_POST['currentDate'];

	

		$mysqli = getDB();

		echo getUserConversations($mysqli, $userID);

		$mysqli->close();

	
?>