<?php
	include_once("databasehelper.php");
	$messageID = $_POST['messageID'];
	$conversationID = $_POST['conversationID'];
	$userID = $_POST['userID'];

	// Required field names
	$required = array('conversationID', 'userID', 'messageID');

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

		echo updateLastReadMessage($mysqli, $messageID, $conversationID, $userID);

		$mysqli->close();

	}
?>