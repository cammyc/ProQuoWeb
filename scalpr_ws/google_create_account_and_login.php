<?php
	include_once("databasehelper.php");

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$displayPicURL = $_POST['displayPicURL'];
	$googleID = $_POST['googleID'];

	// Required field names
	$required = array('firstName', 'lastName', 'email', 'googleID');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	    $error = true;
	  }
	}

	if ($error) {
	  echo -1;//i think error is here
	} else {
	 	$mysqli = getDB();

		echo createAccountGoogleLogin($mysqli,$firstName, $lastName, $email, $displayPicURL, $googleID);

		$mysqli->close();
	}

	
?>