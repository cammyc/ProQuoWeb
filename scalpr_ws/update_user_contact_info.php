<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$user = new UserProfile();

	$user->userID = $_POST['userID'];
	$user->firstName = $_POST['firstName'];
	$user->lastName = $_POST['lastName'];
	$user->phoneNumber = $_POST['phoneNumber'];
	$user->email = $_POST['email'];


		$mysqli = getDB();

		echo UpdateUserContactInfo($mysqli, $user);

		$mysqli->close();
	
?>