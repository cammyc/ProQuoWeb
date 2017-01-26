<?php
	include_once("databasehelper.php");
	$conversationID = $_POST['conversationID'];

	

		$mysqli = getDB();

		echo json_encode(getConversationMessages($mysqli, $conversationID));

		$mysqli->close();

	
?>