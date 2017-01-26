<?php
	use google\appengine\api\cloud_storage\CloudStorageTools;
	include_once("databasehelper.php");

	$creatorID = $_POST['creatorID'];
	$attractionID = $_POST['attractionID'];

	

		$mysqli = getDB();

		// //delete image from storage before removing row from DB
		// $url = getAttractionImage($mysqli, $attractionID);

		// $imageType = exif_imagetype($url);

		// if ($imageType == 2) {
		// 	$imageType = ".jpg";
		// }else if ($imageType == 3){
		// 	$imageType = ".png";
		// }else if ($imageType == 1){
		// 	$imageType = ".gif";
		// }

		// $filePath = "gs://attraction-images/".$attractionID.$imageType;
		// unlink($filePath);

		//ABOVE IS NO LONGER NEEDED BECAUSE ATTRACTION IS SAVED
		echo deleteAttraction($mysqli, $creatorID, $attractionID);

		$mysqli->close();
	
?>