<?php
	include_once("databasehelper.php");
	$conversationID = $_POST['conversationID'];
	$userID = $_POST['userID'];

	// Required field names
	$required = array('conversationID','userID');

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

		echo json_encode(getNewConversationMessages($mysqli, $conversationID, $userID));

		$mysqli->close();

	}
?>