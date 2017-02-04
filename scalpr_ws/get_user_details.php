<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$userID = $_POST['userID'];

	
		$mysqli = getDB();

		$data = getUserDetails($mysqli, $userID);

		echo ($data == null) ? "0" : json_encode($data);

		$mysqli->close();
	
?>