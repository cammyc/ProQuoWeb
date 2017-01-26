<?php
	include_once("databasehelper.php");
	$userID = $_POST['userID'];
	//$currentDate = $_POST['currentDate'];

	

		$mysqli = getDB();

		echo getUserConversations($mysqli, $userID);

		$mysqli->close();

	
?>