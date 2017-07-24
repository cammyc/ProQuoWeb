<?php

	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	$acct = \Stripe\Account::retrieve($stripeDetails->connectID);

	$token = \Stripe\Token::create(array(
			  "card" => $stripeDetails->paymentID
			));

	$acct->external_accounts->create(array("external_account" => $token->id));

  	echo json_encode($acct);
  	// echo "<br><br>";
  	// echo json_encode($cus);

?>