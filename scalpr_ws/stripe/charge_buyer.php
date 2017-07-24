<?php
	
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	// $buyerID = $_POST["buyerID"];
	// $currency = $_POST["currency"];
	$userID = 1;

	$mysqli = getDB();

	$stripeDetails = getStripeAccount($mysqli, $userID);

	$customer = \Stripe\Customer::retrieve($stripeDetails->customerID);


	$result = \Stripe\Charge::create(array(
	  "amount" => 2000,
	  "currency" => "usd",
	  "customer" => $customer->id,
	  "source" => $customer->default_source, //this isn't gonna work for a bank account
	  "description" => "Test charge"
	));

	echo json_encode($result);

?>