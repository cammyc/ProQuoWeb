<?php
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	// $userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	// /* REQUIRED SECURITY INFO BELOW - Billing info for customer and connected account*/
	$country = $_POST["country"]; //ISO 3166-1 alpha-2 country code.
	$city = $_POST["city"];
	$line1 = $_POST["addressLine"];
	$postalCode = $_POST["postalCode"];
	$provinceState = $_POST["provinceState"];
	$receivalType = $_POST["receivalType"]; //"card" or "bank_account"
	$token = $_POST["tokenID"];

	// /* Additional connected account info */
	// $firstname = "john";//- get from db
	// $lastname = "smith"; //- get from db
	// $email = null; // get from db
	// $dobDay = $_POST["dobDay"];
	// $dobMonth = $_POST["dobMonth"];
	// $dobYear = $_POST["dobYear"];

	$userID = 1;

	try {
		$mysqli = getDB();

		$user = getUserDetails($mysqli, $userID);

		$address = [
			"city" => $city,
			"line1" => $line1,
			"postal_code" => $postalCode,
			"state" => $provinceState
		];

		// $dateOfBirth = [
		// 	"day" => $dobDay,
		// 	"month" => $dobMonth,
		// 	"year" => $dobYear
		// ];

		$legalEntity = [
			"first_name" => $user->firstName,
			"last_name" => $user->lastName,
			"address" => $address,
			// "dob" => $dateOfBirth,
			"type" => "individual" //could be company but not supporting that yet
		];

		$payoutSchedule = [
			"delay_days" => 2,
			"interval" => "daily"
		];

		$acct = \Stripe\Account::create(array(
		    "country" => $country,
		    "type" => "custom",
		    "legal_entity" => $legalEntity,
		    "payout_schedule" => $payoutSchedule
		));

		$acct->external_accounts->create(array("external_account" => $token));

		$acct->tos_acceptance->date = time();
		// Assumes you're not using a proxy
		$acct->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
		$acct->save();

		// $token = \Stripe\Source::create(array(
		//   "customer" => $customer->id,
		//   "original_source" => $source->id,
		//   "usage" => "reusable",
		// ), array("stripe_account" => $acct->id));

		// echo json_encode($token);

		$result = saveStripeAccountReceivalMethod($mysqli, $userID, $acct->id, $receivalType); //"bank_account" is other option

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