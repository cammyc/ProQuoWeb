<?php
	include_once("databasehelper.php");

	$oldIDs = $_POST['oldIDs'];
	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];
	$searchViewQuery = $_POST['searchViewQuery'];

	// Required field names
	$required = array('latBoundLeft', 'latBoundRight', 'lonBoundLeft', 'lonBoundRight', 'currentDate');

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

		$idArray = (!empty($oldIDs)) ? explode(",", $oldIDs) : array();

		$mysqli = getDB();

		echo json_encode(getNewAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate, $idArray, $searchViewQuery));

		$mysqli->close();
	}
?>