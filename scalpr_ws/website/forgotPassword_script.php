<?php
use google\appengine\api\mail\Message;
include_once("../databasehelper.php");

$email = $_POST["email"];

$mysqli = getDB();

$userID = validateUserEmail($mysqli, $email);

if($userID > 0){

	$token = Security::createPasswordResetToken($mysqli, $userID, $email);

	if($token != -1){
		try {
		    $message = new Message();
		    $message->setSender('ProQuo@scalpr-143904.appspotmail.com');
		    $message->addTo($email);
		    $message->setSubject('Password Reset - Do Not Reply');
		    $link = 'www.proquoapp.com/resetPassword.php?token='.urlencode($token).'';

		    $imageURL = 'https://storage.googleapis.com/proquo-images/proquogreen_nobg.png';

		    $message->setHtmlBody('<html><body><center><img style="width: 100px; height: 100px;" src="'.$imageURL.'" /> <br> <p>Please follow the link below to reset your password</p> <br> <a href="'.$link.'">Link</></center></body></html>');
		    $message->send();
		    echo 1;
		} catch (InvalidArgumentException $e) {
		    echo 0;
		}
	}else{
		echo 0;
	}

	
}else{
	echo -1;
}

$mysqli->close();

?>