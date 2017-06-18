<?php
	include_once("databasehelper.php");

	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);
	$facebookID = $_POST['facebookID'];

	$mysqli = getDB();

	echo connectFacebookAccount($mysqli, $userID, $facebookID);

	$mysqli->close();

?>