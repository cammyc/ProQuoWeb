<?php

	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	// $acct = \Stripe\Account::retrieve($stripeDetails->connectID);

	// $cus = \Stripe\Customer::retrieve($stripeDetails->customerID);

		$array = \Stripe\Account::retrieve($stripeDetails->connectID)->external_accounts->all(array(
  	'limit'=>1, 'object' => $stripeDetails->paymentType));

	$payment = $array->data[0];

	echo json_encode($payment);

  	// echo json_encode($acct);
  	// echo "<br><br>";
  	// echo json_encode($cus);

?>