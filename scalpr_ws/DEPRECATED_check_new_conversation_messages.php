<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$userID = $_POST['userID'];
	$appVersion = $_POST['appVersion'];
	$appType = $_POST['appType'];

	

		$mysqli = getDB();

		$result = checkNewMessages($mysqli, $userID, $appVersion, $appType);

		echo ($result == 0) ? 0 : json_encode($result);

		$mysqli->close();

	
?>