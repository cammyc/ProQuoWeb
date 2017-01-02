<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$fbID = $_POST['facebookID'];

	// Required field names
	$required = array('firstName', 'lastName', 'facebookID'); //don't check email, it might be null

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

		echo createAccountFBLogin($mysqli,$firstName, $lastName, $email, $fbID);

		$mysqli->close();
	}

	
?>