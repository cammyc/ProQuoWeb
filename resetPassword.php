<?php
	include_once("scalpr_ws/databasehelper.php");
	$token = urldecode($_GET['token']);

	$result = Security::authenticatePasswordResetToken($token);

	if($result > 0){
		echo "lets reset that password!";
	}else if ($result == 0){
		echo "token not found";
	}else{
		echo "invalid token";
	}

?>