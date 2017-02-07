<?php
	use google\appengine\api\cloud_storage\CloudStorageTools;
	include_once("databasehelper.php");
	$creatorID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$attraction = new attraction();
	$attraction->creatorID = $creatorID;
	$attraction->attractionID = $_POST['attractionID'];
	$attraction->venueName = $_POST['venueName'];
	$attraction->name = $_POST['attractionName'];
	$attraction->ticketPrice = $_POST['ticketPrice'];
	$attraction->numTickets = $_POST['numberOfTickets'];
	$attraction->description = $_POST['description'];
	$attraction->date = $_POST['date'];
	$attraction->imageURL = $_POST['imageURL'];

	

		$mysqli = getDB();
		if (strcasecmp($attraction->imageURL, getAttractionImage($mysqli, $attraction->attractionID)) != 0){
			$imageType = exif_imagetype($attraction->imageURL);

			if ($imageType == 2) {
				$imageType = ".jpg";
			}else if ($imageType == 3){
				$imageType = ".png";
			}else if ($imageType == 1){
				$imageType = ".gif";
			}

			$filePath = "gs://attraction-images/".$attraction->attractionID.$imageType;
			unlink($filePath);

			$options = ['gs' => ['acl' => 'public-read']];
			$context = stream_context_create($options);
			$res = file_put_contents($filePath, file_get_contents($attraction->imageURL), 0, $context);

			if ($res !== false){
				$image_url = CloudStorageTools::getPublicUrl($filePath, false);
				$attraction->imageURL = $image_url;
			}
		}

		//putting after because $attraction->imageURL will be changed if image changed
		echo updateAttractionDetails($mysqli,$attraction);
		$mysqli->close();
	
?>