<?php
	include_once("databasehelper.php");
	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	// $userID = $_POST['userID'];
	$deviceToken = $_POST['deviceToken'];

	
		$mysqli = getDB();

		$result = updateIOSDeviceToken($mysqli, $userID, $deviceToken);

		echo $result;

		$mysqli->close();

	
?>