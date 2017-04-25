<?php
	use google\appengine\api\mail\Message;
	include_once("databasehelper.php");
	require_once __DIR__ . "/website/php_vendor/vendor/autoload.php";
	use Twilio\Rest\Client;

	$buyerID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$attractionID = $_POST['attractionID'];
	// $buyerID = $_POST['buyerID'];
	$attractionName = $_POST['attractionName'];

	

		$mysqli = getDB();

		$convoAlreadyCreated = ConvoCreatedCheck($mysqli, $attractionID, $buyerID);

		if($convoAlreadyCreated){
			echo CreateConversation($mysqli, $attractionID, $buyerID, $attractionName);

		}else{
			echo CreateConversation($mysqli, $attractionID, $buyerID, $attractionName);
			SMSEmailNotifyPoster($mysqli, $attractionID, $buyerID, $attractionName);
		}

		$mysqli->close();


	function SMSEmailNotifyPoster($mysqli, $attractionID, $contacterID, $attractionName){

		$contactingUser = getUserContactInfo($mysqli, $contacterID);

		$attraction = getSingleAttraction($mysqli, $attractionID);

		if(!empty($attraction->attractionID)){

			$receivingUser = getUserContactInfo($mysqli, $attraction->creatorID);

			if(!empty($receivingUser->phoneNumber)){

				 // Step 2: set our AccountSid and AuthToken from https://twilio.com/console
			    $AccountSid = "ACd55e94f84043c867f80a4b76dabaa334";
			    $AuthToken = "1a36eb0436a1d1341ede2fe626e3698d";

			    // Step 3: instantiate a new Twilio Rest Client
			    $client = new Client($AccountSid, $AuthToken);

			    $message = "";

			    if ($attraction->postType == 1){
			    	 $message = $contactingUser->firstName." ".$contactingUser->lastName." just contacted you on BeLive about your ".$attraction->name." at ".$attraction->venueName." post! If you would no longer like to be notified about new buyers simply take down your post.";
			    }else{
			    	$message = $contactingUser->firstName." ".$contactingUser->lastName." just contacted you on BeLive about your ".$attraction->name." at ".$attraction->venueName." request! If you would no longer like to be notified about new sellers simply take down your request.";
			    }


			    // Step 5: Loop over all our friends. $number is a phone number above, and 
			    // $name is the name next to it
			    $sms = $client->account->messages->create(

			            // the number we are sending to - Any phone number
			            $receivingUser->phoneNumber,

			            array(
			                // Step 6: Change the 'From' number below to be a valid Twilio number 
			                // that you've purchased
			                'from' => "+17209999644", 
			                
			                // the sms body
			                'body' => $message
			            )
			        );

			}else if(!empty($receivingUser->email)){
				try {
				    $message = new Message();
				    $message->setSender('BeLive@scalpr-143904.appspotmail.com');
				    $message->addTo($receivingUser->email);

				    if ($attraction->postType == 1){
				    	$message->setSubject($attraction->name." - BeLive Post");

					    $message->setHtmlBody('<html><body><center><img style="width: 125px; height: auto;" src="'.$attraction->imageURL.'" /> <br> <p style="font-size: 13pt;">Someone just contacted you on BeLive about your '.$attractionName.' at '.$attraction->venueName.' post! </p><br><br><p>If you would no longer like to be notified about new buyers simply take down your post.</p></center></body></html>');
					    $message->send();
				    }else{
				    	$message->setSubject($attraction->name." - BeLive Request");

					    $message->setHtmlBody('<html><body><center><img style="width: 125px; height: auto;" src="'.$attraction->imageURL.'" /> <br> <p style="font-size: 13pt;">Someone just contacted you on BeLive about your '.$attractionName.' at '.$attraction->venueName.' request!</p><br><br><p>If you would no longer like to be notified about new sellers simply take down your request.</p></center></body></html>');

					    $message->send();
				    }

			    
				} catch (InvalidArgumentException $e) {
				}
			}
		}

	}

	
?>