<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];

	// Required field names
	$required = array('userID');

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

		$data = getUserDetails($mysqli, $userID);

		echo ($data == null) ? "0" : json_encode($data);

		$mysqli->close();
	}
?>