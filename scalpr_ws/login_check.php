<?php
	include_once("databasehelper.php");

	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];
	$retrieveUserInfo = $_POST['retrieveUserInfo'];

	// Required field names
	$required = array('emailPhone', 'password', 'retrieveUserInfo');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	    $error = true;
	  }
	}

	if ($error) {
	  echo 0;
	} else {

		$mysqli = getDB();

		echo loginCheck($mysqli,$emailPhone,$password,$retrieveUserInfo);

		$mysqli->close();
	}
?>