<?php
	include_once("databasehelper.php");
	$userID = $_POST['userID'];
	$appVersion = $_POST['appVersion'];
	$appType = $_POST['appType'];

	// Required field names
	$required = array('userID', 'appVersion', 'appType');

	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	  	if($_POST[$field] != 0){
	  		$error = true; //lastMessageID may be 0, in which case empty returns true
	  	}
	  }
	}

	if ($error) {
	  echo 0;
	} else {

		$mysqli = getDB();

		$result = checkNewMessages($mysqli, $userID, $appVersion, $appType);

		echo ($result == 0) ? 0 : json_encode($result);

		$mysqli->close();

	}
?>