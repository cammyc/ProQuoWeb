<?php
	include_once("databasehelper.php");
	$userID = $_POST['userID'];
	$deviceToken = $_POST['deviceToken'];

	
		$mysqli = getDB();

		$result = updateIOSDeviceToken($mysqli, $userID, $deviceToken);

		echo $result;

		$mysqli->close();

	
?>