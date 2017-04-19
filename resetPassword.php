<?php
	include_once("scalpr_ws/databasehelper.php");
	try{
		$token = urldecode($_GET['token']);
	}catch(\Exception $ex){
		include("invalidToken.html"); //should be executed if no token in URL
	}

	$userID = Security::authenticatePasswordResetToken($token);

	if($userID > 0){
		include("validToken.html");
	}else if ($userID == 0){
		include("invalidToken.html");
	}else{
		include("invalidToken.html");
	}

?>
