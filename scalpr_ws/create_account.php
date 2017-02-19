<?php
	include_once("databasehelper.php");

	// Required field names
	$required = array('firstname', 'lastname', 'emailPhone'); //password can be empty so don't check - eventually need email and phone verif.

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	    $error = true;
	  }
	}

	if ($error) {
	  echo "0";
	  exit(0);
	}

	$firstName = $_POST['firstname'];
	$lastName = $_POST['lastname'];
	$emailPhone = $_POST['emailPhone'];
	$password = $_POST['password'];

 	$mysqli = getDB();

	echo createAccount($mysqli,$firstName,$lastName,$emailPhone,$password);

	$mysqli->close();

?>