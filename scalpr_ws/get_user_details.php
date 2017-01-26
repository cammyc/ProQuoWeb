<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];

	
		$mysqli = getDB();

		$data = getUserDetails($mysqli, $userID);

		echo ($data == null) ? "0" : json_encode($data);

		$mysqli->close();
	
?>