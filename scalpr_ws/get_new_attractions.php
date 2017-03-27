<?php
	include_once("databasehelper.php");

	$oldIDs = $_POST['oldIDs'];
	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];
	$searchViewQuery = $_POST['searchViewQuery'];

	$mysqli = getDB();

	$idArray = (!empty($oldIDs)) ? explode(",", $oldIDs) : array();

	if(!empty($_POST['filters'])){ //will be empty on phones that havent been updated

		$filter = new Filters();
		$filter->startDate = $_POST['filters']['startDate'];
		$filter->endDate = $_POST['filters']['endDate'];
		$filter->showRequested = $_POST['filters']['requestedTickets'];
		$filter->showSelling = $_POST['filters']['sellingTickets'];
		$filter->minPrice = $_POST['filters']['minPrice'];
		$filter->maxPrice = $_POST['filters']['maxPrice'];
		$filter->numTickets = $_POST['filters']['numTickets'];

		echo json_encode(getNewAttractions($mysqli, $filter, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate, $idArray, $searchViewQuery));

	}else{//below is executed on phones that haven't updated app, still need to update getNewAttractions function

		echo json_encode(getNewAttractions($mysqli, null, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate, $idArray, $searchViewQuery));

	}

	$mysqli->close();

	
?>