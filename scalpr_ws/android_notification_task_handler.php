<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$messages = json_decode($_POST['messages']);

	$mysqli = getDB();

	$tokens = retrieveSingleUserAndroidDeviceTokens($mysqli, $userID);

	if(sizeof($tokens) > 0){
		define( 'API_ACCESS_KEY', 'AAAAl0GBHVA:APA91bGZG2ocZlAYZOjjSZRRmaph4RC8K7YfoJqt5dB8rrlsz07A3QWEbBtA1Qqz7uOFQSPVHSXIHHe-BPKlr0s-9xwfO0kdL9Ssmj4JLGAa-JcOep1qcm4eAU8W4sVXMoAUHjeoqEzy' );

			for($k = 0; $k < sizeof($tokens); $k++){
				$udt = $tokens[$k];

					for ($i = (sizeof($messages)-1); $i >= 0; $i--){
						$mesNot = $messages[$i];
						$message = $mesNot->yourName.": ".$mesNot->message;
						#prep the bundle
					    $msg = array
					          (
							'body' 	=> $mesNot->message,
							'title'	=> $mesNot->yourName,
					             	'icon'	=> 'myicon',/*Default Icon*/
					              	'sound' => 'mySound'/*Default sound*/
									//'data' => $mesNot
					          );

						$fields = array
								(
									'to' => $udt->deviceToken,
									//'notification'	=> $msg,
									'data' => $mesNot
								);
					
						$headers = array
								(
									'Authorization: key=' . API_ACCESS_KEY,
									'Content-Type: application/json'
								);

						#Send Reponse To FireBase Server	
						$ch = curl_init();
						curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
						curl_setopt( $ch,CURLOPT_POST, true );
						curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
						curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
						curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields));
						$result = curl_exec($ch );
						curl_close( $ch );
						$json_res = json_decode($result, true);
						if ($json_res['success'] == true){
							updateLastNotifiedMessage($mysqli, $mesNot->messageID, $mesNot->convoID, $udt->userID);
							http_response_code(200);//200-299 is success
						}else{
							http_response_code(199);//error
						}
					}
				
			}
	}else{
		http_response_code(200);//200-299 is success
	}

	

	$mysqli->close();


?> 