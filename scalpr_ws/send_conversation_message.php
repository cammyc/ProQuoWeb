<?php
	include_once("databasehelper.php");
	$conversationID = $_POST['conversationID'];
	$senderID = $_POST['senderID'];
	$message = $_POST['message'];

	
		$mysqli = getDB();

		echo sendConversationMessage($mysqli, $conversationID, $senderID, $message);

		$mysqli->close();

	
?>