<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$password = $_POST['password'];

	
		$mysqli = getDB();

		echo UpdateUserPassword($mysqli, $userID, $password);

		$mysqli->close();
	
?>