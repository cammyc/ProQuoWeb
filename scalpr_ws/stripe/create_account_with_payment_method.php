<?php
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");


	// /* REQUIRED SECURITY INFO BELOW - Billing info for customer and connected account*/
	$country = $_POST["country"]; //ISO 3166-1 alpha-2 country code.
	$city = $_POST["city"];
	$line1 = $_POST["addressLine"];
	$postalCode = $_POST["postalCode"];
	$provinceState = $_POST["provinceState"];

	$cardToken = $_POST["tokenID"];
	$cardID = $_POST["cardID"];

	$userID = 1;  	// $userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	try {
		$mysqli = getDB();

		$user = getUserDetails($mysqli, $userID);

		$source = \Stripe\Source::create(array("token" => $cardToken, "type" => "card")); 


		$customer = \Stripe\Customer::create(array(
			  "description" => "Customer for ".$user->firstName." ".$user->lastName,
			  // "email" => $email,
			  "source" => $source->id
		));

		$result = saveStripeAccountPaymentMethod($mysqli, $userID, $customer->id, $source->id, $cardID, "card");

		if(!$result){
			$mysqli->close();
			echo "-2";
			return;
		}

		$mysqli->close();

	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	  echo -1;
	  $mysqli->close();
	  return;
	}

	echo 1;


?>