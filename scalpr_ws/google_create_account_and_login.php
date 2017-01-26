<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$displayPicURL = $_POST['displayPicURL'];
	$googleID = $_POST['googleID'];

	
	 	$mysqli = getDB();

		echo createAccountGoogleLogin($mysqli,$firstName, $lastName, $email, $displayPicURL, $googleID);

		$mysqli->close();
	

	
?>