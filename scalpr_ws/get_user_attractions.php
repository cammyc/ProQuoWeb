<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$currentDate = $_POST['currentDate'];

	// Required field names
	$required = array('userID', 'currentDate');

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

		echo json_encode(getUserAttractions($mysqli, $userID, $currentDate));

		$mysqli->close();

	}
?>