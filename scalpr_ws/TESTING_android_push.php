<?php
#API access key from Google API's Console
    define( 'API_ACCESS_KEY', 'AAAAl0GBHVA:APA91bGZG2ocZlAYZOjjSZRRmaph4RC8K7YfoJqt5dB8rrlsz07A3QWEbBtA1Qqz7uOFQSPVHSXIHHe-BPKlr0s-9xwfO0kdL9Ssmj4JLGAa-JcOep1qcm4eAU8W4sVXMoAUHjeoqEzy' );

    //$registrationIds = $_GET['id'];
#prep the bundle
     $msg = array
          (
		'body' 	=> 'Body  Of Notification',
		'title'	=> 'Title Of Notification',
             	'icon'	=> 'myicon',/*Default Icon*/
              	'sound' => 'mySound'/*Default sound*/
          );
	$fields = array
			(
				'to'		=> 'fQRhE3WBGrw:APA91bGc5e6cxu6YWSw0AIOpebMCHq9BHyAyFVNQ7ThZ-A78bITiX63fQVkgpg7XM8I7NjmkamT_u4ZwbBf1IvzuXuzdGvKl8cTQ213glUqepX67PfF2ECBGYiIJBIpIJ1GVjhfxnnGh',
				'notification'	=> $msg
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
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
#Echo Result Of FireBase Server
//echo $result;
$temp = json_decode($result, true);
//var_dump($temp);

if($temp["failure"]){
	if(strcasecmp($temp["results"][0]["error"], "NotRegistered") == 0){
	print("deleteToken");
	}else{
		var_dump($temp["results"][0]);
	}
}else{
	var_dump($temp["results"][0]);
}



//print_r($temp);
// if ($result  false){
// 	echo "test";
// }
?>