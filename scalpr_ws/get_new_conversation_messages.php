<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$conversationID = $_POST['conversationID'];
	$userID = $_POST['userID'];

	
		$mysqli = getDB();

		echo json_encode(getNewConversationMessages($mysqli, $conversationID, $userID));

		$mysqli->close();

	
?>