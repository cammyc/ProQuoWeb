<?php
	include_once("databasehelper.php");
	$conversationID = $_POST['conversationID'];
	$senderID = $_POST['senderID'];
	$message = $_POST['message'];

	// Required field names
	$required = array('conversationID', 'senderID', 'message');

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

		echo sendConversationMessage($mysqli, $conversationID, $senderID, $message);

		$mysqli->close();

	}
?>