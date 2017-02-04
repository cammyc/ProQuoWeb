<?php
	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	$conversationID = $_POST['conversationID'];
	$senderID = $_POST['senderID'];
	$message = $_POST['message'];

	
		$mysqli = getDB();

		echo sendConversationMessage($mysqli, $conversationID, $senderID, $message);

		sendIOSNotifications($mysqli);
		sendAndroidNotifications($mysqli);
		
		
		$mysqli->close();


		function sendAndroidNotifications($mysqli){
			define( 'API_ACCESS_KEY', 'AAAAl0GBHVA:APA91bGZG2ocZlAYZOjjSZRRmaph4RC8K7YfoJqt5dB8rrlsz07A3QWEbBtA1Qqz7uOFQSPVHSXIHHe-BPKlr0s-9xwfO0kdL9Ssmj4JLGAa-JcOep1qcm4eAU8W4sVXMoAUHjeoqEzy' );

			$userDeviceTokens = retrieveAllAndroidUserDevicesTokens($mysqli);

			for($k = 0; $k < sizeof($userDeviceTokens); $k++){
				$udt = $userDeviceTokens[$k];
				$result = checkNewMessages($mysqli, $udt->userID, getMinAndroidVersion(), 1);

				if ($result != 0) {

					for ($i = (sizeof($result)-1); $i >= 0; $i--){
						$mesNot = $result[$i];
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
						}else{
							//echo "failure";
						}
					}
				}
			}
		}


		function sendIOSNotifications($mysqli){
			$userDeviceTokens = retrieveAllIOSUserDevicesTokens($mysqli);

			for($k = 0; $k < sizeof($userDeviceTokens); $k++){
				$udt = $userDeviceTokens[$k];

				$result = checkNewMessages($mysqli, $udt->userID, getMiniOSVersion(), 2);

				if ($result != 0) {
					$passphrase = '';

					$ctx = stream_context_create();
					stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns.pem');
					stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

					// Open a connection to the APNS server
					$fp = stream_socket_client(
					  'ssl://gateway.sandbox.push.apple.com:2195', $err,
					  $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

					if (!$fp)
						echo -2; //failed to connect error
					  //exit("Failed to connect: $err $errstr" . PHP_EOL);

					//echo 'Connected to APNS' . PHP_EOL;

					for ($i = (sizeof($result)-1); $i >= 0; $i--){
						$mesNot = $result[$i];
						$message = $mesNot->yourName.": ".$mesNot->message;

						// Create the payload body
						$body['aps'] = array(
						  'alert' => $message,
						  'sound' => 'default',
						  );

						$body['data'] = json_encode($mesNot);

						// Encode the payload as JSON
						$payload = json_encode($body);

						// Build the binary notification
						$msg = chr(0) . pack('n', 32) . pack('H*', $udt->deviceToken) . pack('n', strlen($payload)) . $payload;

						// Send it to the server
						$apnRes = fwrite($fp, $msg, strlen($msg));
						if($apnRes){
							updateLastNotifiedMessage($mysqli, $mesNot->messageID, $mesNot->convoID, $udt->userID);
						}
					}

					// Close the connection to the server
					fclose($fp);
				}
				// else{
				// 	echo 0;
				// }
			}
		}

	
?>