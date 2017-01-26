<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstname'];
	$lastName = $_POST['lastname'];
	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];

	
	 	$mysqli = getDB();

		echo createAccount($mysqli,$firstName,$lastName,$emailPhone,$password);

		$mysqli->close();
	

	
?>