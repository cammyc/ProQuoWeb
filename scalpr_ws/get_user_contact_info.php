<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];

	

		$mysqli = getDB();

		$data = getUserContactInfo($mysqli, $userID);

		echo ($data == 0) ? "0" : json_encode($data);

		$mysqli->close();
	
?>