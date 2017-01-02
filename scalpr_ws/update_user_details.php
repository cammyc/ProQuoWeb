<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$password = $_POST['password'];

	// Required field names
	$required = array('userID', 'password');

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

		echo UpdateUserPassword($mysqli, $userID, $password);

		$mysqli->close();
	}
?>