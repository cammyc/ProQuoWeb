<?php
	include_once("databasehelper.php");

	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];
	$retrieveUserInfo = $_POST['retrieveUserInfo'];


		$mysqli = getDB();

		echo loginCheck($mysqli,$emailPhone,$password,$retrieveUserInfo);

		$mysqli->close();
	
?>