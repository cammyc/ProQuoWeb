<?php
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	/* REQUIRED SECURITY INFO BELOW - Billing info for customer and connected account*/
	$country = $_POST["country"]; //ISO 3166-1 alpha-2 country code.
	$city = $_POST["city"];
	$line1 = $_POST["line1"];
	$postalCode = $_POST["postalCode"];
	$provinceState = $_POST["provinceState"];

	/* CARD INFO */
	$paymentType = "card" //could be account
	$expMonth = $_POST["expirationMonth"];
	$expYear = $_POST["expirationYear"];
	$number = $_POST["number"];
	$cvc = $_POST["cvc"];
	$nameOnCard = $_POST["nameOnCard"];
	$currency = $_POST["currency"]; //Three-letter ISO currency code

	/* Additional connected account info */
	$firstname = "john";//- get from db
	$lastname = "smith"; //- get from db
	$email = null; // get from db
	$dobDay = $_POST["dobDay"];
	$dobMonth = $_POST["dobMonth"];
	$dobYear = $_POST["dobYear"];


	/* SOURCE will be used for connected account external account and customer payment method */

	$source = [
		  "object" => $paymentType,
		  "exp_month" => $expMonth,
		  "exp_year" => $expYear,
		  "number" => $number,
		  "currency" => $currency,
		  "cvc" => $cvc
		];

	$customerSuccessful = true;

	try {
		$customer = \Stripe\Customer::create(array(
			  "description" => "Customer for".$firstname." ".$lastname,
			  "email" => $email,
			  "source" => $source // obtained with Stripe.js
			));
	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	  return;
	}

	try {
		$address = [
			"city" => $city,
			"line1" => $line1,
			"postal_code" => $postalCode,
			"state" => $provinceState
		];

		$dateOfBirth = [
			"day" => $dobDay,
			"month" => $dobMonth,
			"year" => $dobYear
		]

		$legal_entity = [
			"first_name" => $firstname,
			"last_name" => $lastname,
			"address" => $address,
			"dob" => $dateOfBirth,
			"type" => "individual" //could be company but not supporting that yet
		]

		$acct = \Stripe\Account::create(array(
		    "country" => $country,
		    "type" => "custom",
		    "legal_entity" => $legal_entity
		));

	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	  return;
	}
	

?>