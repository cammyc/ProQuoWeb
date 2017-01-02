<?php
	include_once("databasehelper.php");
	$conversationID = $_POST['conversationID'];

	// Required field names
	$required = array('conversationID');

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

		echo json_encode(getConversationMessages($mysqli, $conversationID));

		$mysqli->close();

	}
?>