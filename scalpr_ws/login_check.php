<?php
	include_once("databasehelper.php");

	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];


		$mysqli = getDB();

		echo loginCheck($mysqli,$emailPhone,$password);

		$mysqli->close();
	
?>