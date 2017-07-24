<?php
	
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	// $buyerID = $_POST["buyerID"];
	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	$array = \Stripe\Account::retrieve($stripeDetails->connectID)->external_accounts->all(array(
  	'limit'=>1, 'object' => $stripeDetails->paymentType));

	$payment = $array->data[0];

	$result = \Stripe\Transfer::create(array(
	  "amount" => 400,
	  "currency" => "usd",
	  "source_transaction" => "ch_1AgIScKaY55aUmCB9z10CKjJ",
	  "destination" => $payment->account,
	));

	// $result = \Stripe\Payout::create(array(
	//   "amount" => 100,
	//   "currency" => "usd",
	//   "destination" => $payment->card
	//   ), array("stripe_account" => $payment->account));

	echo json_encode($result);

?>