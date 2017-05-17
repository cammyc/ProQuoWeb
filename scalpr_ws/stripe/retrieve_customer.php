<?php
	require_once '../databasehelper.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	try {
	  $customer = \Stripe\Customer::retrieve($userID);
	  header('Content-Type: application/json');
	  echo $customer->jsonSerialize();
	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	}
?>