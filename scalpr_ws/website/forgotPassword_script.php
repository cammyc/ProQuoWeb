<?php
use google\appengine\api\mail\Message;
include_once("../databasehelper.php");
require_once __DIR__ . "/php_vendor/vendor/autoload.php";
use Twilio\Rest\Client;

$emailPhone = $_POST["emailPhone"];
$isEmail = (strcmp($_POST["isEmail"],"1") == 0) ? true : false;


$mysqli = getDB();

$userID = ($isEmail) ? validateUserEmail($mysqli, $emailPhone) : validateUserPhone($mysqli, $emailPhone);

if($userID > 0){

	$token = Security::createPasswordResetToken($mysqli, $userID, $emailPhone);

	if($token != -1){
		$link = 'www.proquoapp.com/resetPassword.php?token='.urlencode($token).'';

		if(!$isEmail){
	
		    // Step 2: set our AccountSid and AuthToken from https://twilio.com/console
		    $AccountSid = "ACd55e94f84043c867f80a4b76dabaa334";
		    $AuthToken = "1a36eb0436a1d1341ede2fe626e3698d";

		    // Step 3: instantiate a new Twilio Rest Client
		    $client = new Client($AccountSid, $AuthToken);


		    // Step 5: Loop over all our friends. $number is a phone number above, and 
		    // $name is the name next to it
		    $sms = $client->account->messages->create(

		            // the number we are sending to - Any phone number
		            $emailPhone,

		            array(
		                // Step 6: Change the 'From' number below to be a valid Twilio number 
		                // that you've purchased
		                'from' => "+17209999644", 
		                
		                // the sms body
		                'body' => "Please follow the link to reset your ProQuo password - ".$link
		            )
		        );

		    echo 1;

		}else{
			try {
			    $message = new Message();
			    $message->setSender('ProQuo@scalpr-143904.appspotmail.com');
			    $message->addTo($emailPhone);
			    $message->setSubject('Password Reset - Do Not Reply');

			    $imageURL = 'https://storage.googleapis.com/proquo-images/proquogreen_nobg.png';

			    $message->setHtmlBody('<html><body><center><img style="width: 100px; height: 100px;" src="'.$imageURL.'" /> <br> <p>Please follow the link below to reset your password</p> <br> <a href="'.$link.'">Link</></center></body></html>');
			    $message->send();
			    echo 1;
			} catch (InvalidArgumentException $e) {
			    echo 0;
			}
		}
		
	}else{
		echo 0;
	}

	
}else{
	echo -1;
}

$mysqli->close();

?>