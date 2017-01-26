<?php
	include_once("databasehelper.php");

	$oldIDs = $_POST['oldIDs'];
	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];
	$searchViewQuery = $_POST['searchViewQuery'];

	

		$idArray = (!empty($oldIDs)) ? explode(",", $oldIDs) : array();

		$mysqli = getDB();

		echo json_encode(getNewAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate, $idArray, $searchViewQuery));

		$mysqli->close();
	
?>