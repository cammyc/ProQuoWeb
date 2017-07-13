<?php

	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	$array = \Stripe\Account::retrieve($stripeDetails->connectID)->external_accounts->all(array(
  	'limit'=>1, 'object' => stripeDetails->paymentType));

  	echo json_encode($payment = $array->data[0]);

?>