<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$conversationID = $_POST['conversationID'];

	

		$mysqli = getDB();

		echo json_encode(getConversationMessages($mysqli, $conversationID));

		$mysqli->close();

	
?>