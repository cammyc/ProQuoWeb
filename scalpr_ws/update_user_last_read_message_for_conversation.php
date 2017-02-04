<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$messageID = $_POST['messageID'];
	$conversationID = $_POST['conversationID'];
	$userID = $_POST['userID'];

	$mysqli = getDB();

	echo updateLastReadMessage($mysqli, $messageID, $conversationID, $userID);

	$mysqli->close();

	
?>