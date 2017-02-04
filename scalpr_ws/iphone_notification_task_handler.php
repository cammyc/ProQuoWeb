<?php
	include_once("databasehelper.php");

	$userID = $_POST['userID'];
	$messages = json_decode($_POST['messages']);

	$mysqli = getDB();

	$tokens = retrieveSingleUserIOSDeviceTokens($mysqli, $userID);

	if(sizeof($tokens) > 0){
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

		for($k = 0; $k < sizeof($tokens); $k++){
			$token = $tokens[$k];
			for ($i = (sizeof($messages)-1); $i >= 0; $i--){
				$mesNot = $messages[$i];
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
				$msg = chr(0) . pack('n', 32) . pack('H*', $token->deviceToken) . pack('n', strlen($payload)) . $payload;

				// Send it to the server
				$apnRes = fwrite($fp, $msg, strlen($msg));
				if($apnRes){
					updateLastNotifiedMessage($mysqli, $mesNot->messageID, $mesNot->convoID, $userID);
					http_response_code(200);//200-299 is success

				}else{
					http_response_code(199);//error
				}
			}
		}

		

		// Close the connection to the server
		fclose($fp);
	}else{
		http_response_code(200);//200-299 is success - nothing sent
	}

	

	$mysqli->close();


?> 