<?php

	include_once("databasehelper.php");
	Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	// $userID = 1;
	// $appVersion = $_POST['appVersion'];
	// $appType = $_POST['appType'];
	// $deviceToken = $_POST['deviceToken'];

	// // Required field names
	// $required = array('userID', 'appVersion', 'appType', 'deviceToken');

	// // Loop over field names, make sure each one exists and is not empty
	// $error = false;
	// foreach($required as $field) {
	//   if (empty($_POST[$field])) {
	//   	if($_POST[$field] != 0){
	//   		$error = true; //lastMessageID may be 0, in which case empty returns true
	//   	}
	//   }
	// }

	// if ($error) {
	//   echo 0;
	// } else {

		$mysqli = getDB();

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
			}else{
				echo 0;
			}


		}
		
		$mysqli->close();

	//}
?>