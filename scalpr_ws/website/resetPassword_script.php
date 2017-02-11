<?php
	include_once("../databasehelper.php");

	$password = $_POST["password"];
	$token = urldecode($_POST['token']);

	$userID = Security::authenticatePasswordResetToken($token);


	if($userID > 0){
		$mysqli = getDB();

		$result = updateUserPassword($mysqli, $userID, $password);

		if($result == 1){
			echo "1";
		}else{
			echo "0";
		}

		$mysqli->close();
	}else{
		echo "-1";
	}



?>