<?php
	function encrypt($input, $key) {
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
			$input = pkcs5_pad($input, $size); 
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
			$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
			mcrypt_generic_init($td, $key, $iv); 
			$data = mcrypt_generic($td, $input); 
			mcrypt_generic_deinit($td); 
			mcrypt_module_close($td); 
			$data = base64_encode($data); 
			return $data; 
		} 

	function pkcs5_pad ($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 

	$_SERVER['HTTP_SCALPRVERIFICATION'] = encrypt("WheresTheClosestKanyeTicketAt?", '$c@lPrK3Y1236547');
	include_once("databasehelper.php");
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