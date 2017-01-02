<?php
	include_once("databasehelper.php");

	$attractionID = $_POST['attractionID'];
	$buyerID = $_POST['buyerID'];
	$attractionName = $_POST['attractionName'];

	// Required field names
	$required = array('attractionID', 'buyerID', 'attractionName');

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

		echo CreateConversation($mysqli, $attractionID, $buyerID, $attractionName);

		$mysqli->close();

	}
?>