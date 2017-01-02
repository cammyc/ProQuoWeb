<?php
	include_once("databasehelper.php");

	$user = new UserProfile();

	$user->userID = $_POST['userID'];
	$user->firstName = $_POST['firstName'];
	$user->lastName = $_POST['lastName'];
	$user->phoneNumber = $_POST['phoneNumber'];
	$user->email = $_POST['email'];

	// Required field names
	$required = array('userID', 'firstName', 'lastName');

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

		echo UpdateUserContactInfo($mysqli, $user);

		$mysqli->close();
	}
?>