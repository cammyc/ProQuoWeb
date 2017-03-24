<?php
	include_once("databasehelper.php");

	$oldIDs = $_POST['oldIDs'];
	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];
	$searchViewQuery = $_POST['searchViewQuery'];

	// if(!empty($_POST['startDate'])){ //will be empty on phones that havent been updated

	// 	$filter = new Filter();
	// 	$filter->startDate = $_POST['startDate'];
	// 	$filter->endDate = $_POST['endDate'];
	// 	$filter->showRequested = $_POST['requestedTickets'];
	// 	$filter->showSelling = $_POST['sellingTickets'];
	// 	$filter->priceMin = $_POST['priceMin'];
	// 	$filter->priceMax = $_POST['priceMax'];
	// 	$filter->numTickets = $_POST['numTickets'];

	// }else{//below is executed on phones that haven't updated app, still need to update getNewAttractions function

		$idArray = (!empty($oldIDs)) ? explode(",", $oldIDs) : array();

		$mysqli = getDB();

		echo json_encode(getNewAttractions($mysqli, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate, $idArray, $searchViewQuery));

		$mysqli->close();

	// }
	
?>