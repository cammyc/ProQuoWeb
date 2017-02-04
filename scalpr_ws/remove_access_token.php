<?php
	include_once("databasehelper.php");

	$token = $_POST['accessToken'];

	echo Security::removeToken($token);
	

	
?>