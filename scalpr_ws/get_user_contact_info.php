<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$userID = $_POST['userID'];

	

		$mysqli = getDB();

		$data = getUserContactInfo($mysqli, $userID);

		echo ($data == 0) ? "0" : json_encode($data);

		$mysqli->close();
	
?>