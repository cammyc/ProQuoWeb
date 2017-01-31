<?php
	include_once("databasehelper.php");
	$userID = $_POST['userID'];
	$deviceToken = $_POST['deviceToken'];

	
		$mysqli = getDB();

		$result = updateAndroidDeviceToken($mysqli, $userID, $deviceToken);

		echo $result;

		$mysqli->close();

	
?>