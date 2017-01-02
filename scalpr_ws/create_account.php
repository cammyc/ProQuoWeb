<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstname'];
	$lastName = $_POST['lastname'];
	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];

	// Required field names
	$required = array('firstname', 'lastname', 'emailPhone', 'password');

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

		echo createAccount($mysqli,$firstName,$lastName,$emailPhone,$password);

		$mysqli->close();
	}

	
?>