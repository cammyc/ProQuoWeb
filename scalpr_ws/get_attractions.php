<?php
	include_once("databasehelper.php");

	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];



		$mysqli = getDB();

		echo json_encode(getAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate));

		$mysqli->close();

	
?>