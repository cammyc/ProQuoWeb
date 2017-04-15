<?php
	include_once("databasehelper.php");

	$latBoundLeft = $_POST['latBoundLeft'];
	$latBoundRight = $_POST['latBoundRight'];
	$lonBoundLeft = $_POST['lonBoundLeft'];
	$lonBoundRight = $_POST['lonBoundRight'];
	$currentDate = $_POST['currentDate'];

	$mysqli = getDB();


	if(!empty($_POST['filters'])){ //will be empty on phones that havent been updated
		$filter = new Filters();
		$filter->startDate = $_POST['filters']['startDate'];
		$filter->endDate = $_POST['filters']['endDate'];
		$filter->showRequested = $_POST['filters']['requestedTickets'];
		$filter->showSelling = $_POST['filters']['sellingTickets'];
		$filter->minPrice = $_POST['filters']['minPrice'];
		$filter->maxPrice = $_POST['filters']['maxPrice'];
		$filter->numTickets = $_POST['filters']['numTickets'];

		echo json_encode(getAttractions($mysqli, $filter, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate));

	}else if(!empty($_POST['jsonFilters'])){ //android

		syslog(LOG_INFO, $_POST['jsonFilters']);//printing twice, not working

		$filterArray = json_decode($_POST['jsonFilters']);

		$filter = new Filters();
		$filter->startDate = $filterArray->{'startDate'};
		$filter->endDate = $filterArray->{'endDate'};
		$filter->showRequested = (strcasecmp($filterArray->{'requestedTickets'}, "true") == 0) ? true : false;
		$filter->showSelling = (strcasecmp($filterArray->{'sellingTickets'}, "true") == 0) ? true : false;
		$filter->minPrice = $filterArray->{'minPrice'};
		$filter->maxPrice = $filterArray->{'maxPrice'};
		$filter->numTickets = $filterArray->{'numTickets'};

		echo json_encode(getAttractions($mysqli, $filter, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate));

	}else{//below is executed on phones that haven't updated app, still need to update getNewAttractions function

		echo json_encode(getAttractions($mysqli, null, $latBoundLeft, $latBoundRight, $lonBoundLeft, $lonBoundRight, $currentDate));

	}

	$mysqli->close();

	
?>