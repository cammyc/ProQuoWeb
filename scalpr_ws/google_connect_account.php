<?php
	include_once("databasehelper.php");

	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);
	$googleID = $_POST['googleID'];
	$displayPicURL = $_POST['displayPicURL'];

	$mysqli = getDB();

	echo connectGoogleAccount($mysqli, $userID, $googleID, $displayPicURL);

	$mysqli->close();

?>