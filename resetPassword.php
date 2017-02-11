<?php
	include_once("scalpr_ws/databasehelper.php");
	$token = urldecode($_GET['token']);

	$userID = Security::authenticatePasswordResetToken($token);

	if($userID > 0){
		include("validToken.html");
	}else if ($userID == 0){
		include("invalidToken.html");
	}else{
		include("invalidToken.html");
	}

?>
