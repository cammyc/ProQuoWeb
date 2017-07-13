<?php
	
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	$array = \Stripe\Account::retrieve($stripeDetails->connectID)->external_accounts->all(array(
  	'limit'=>1, 'object' => $stripeDetails->paymentType));

	$payment = $array->data[0];

	$result = \Stripe\Charge::create(array(
	  "amount" => 2000,
	  "currency" => "usd",
	  "source" => $stripeDetails->connectID,
	  "description" => "Test charge"
	));

	echo json_encode($result);

?>