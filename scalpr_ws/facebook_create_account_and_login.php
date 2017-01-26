<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$fbID = $_POST['facebookID'];

	
	 	$mysqli = getDB();

		echo createAccountFBLogin($mysqli,$firstName, $lastName, $email, $fbID);

		$mysqli->close();
	

	
?>