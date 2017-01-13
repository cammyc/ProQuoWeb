<?php
// require __DIR__ . '/vendor/autoload.php';

require_once 'Pushok/AuthProvider/Token.php';
require_once 'Pushok/Client.php';
require_once 'Pushok/Notification.php';
require_once 'Pushok/Payload.php';
require_once 'Pushok/Payload/Alert.php';

$options = [
    'key_id' => 'CCR---4D2', // The Key ID obtained from Apple developer account
    'team_id' => 'F5PZ23FU2E', // The Team ID obtained from Apple developer account
    'app_bundle_id' => 'com.ProQuo.ProQuoApp', // The bundle ID for app obtained from Apple developer account
    'private_key_path' => __DIR__ . 'APNsAuthKey_TLX4YVDFU4.p8', // Path to private key
    'private_key_secret' => null // Private key secret
];

$authProvider = AuthProvider\Token::create($options);

$alert = Alert::create()->setTitle('Hello!');
$alert = $alert->setBody('First push notification');

$payload = Payload::create()->setAlert($alert);

//set notification sound to default
$payload = $payload->setSound('default');

//add custom value to your notification, needs to be customized
$payload = $payload->setCustomValue("key",$value);

$deviceTokens = ['F12DBFB11C07891386826B614ACA4ADA80531FAD1E5DAB89875A954505ADCE65'];

$notifications = [];
foreach ($deviceTokens as $deviceToken) {
    $notifications[] = new Notification($payload,$deviceToken);
}

$client = new Client($authProvider, $production = false);
$client->addNotifications($notifications);



$responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

foreach ($responses as $response) {
    $response->getApnsId();
    $response->getStatusCode();
    $response->getReasonPhrase();
    $response->getErrorReason();
    $response->getErrorDescription();
}