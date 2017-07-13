<?php
	require_once '../databasehelper_dev.php';
	require_once '../vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	$userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);

	// /* REQUIRED SECURITY INFO BELOW - Billing info for customer and connected account*/
	$country = $_POST["country"]; //ISO 3166-1 alpha-2 country code.
	$city = $_POST["city"];
	$line1 = $_POST["addressLine"];
	$postalCode = $_POST["postalCode"];
	$provinceState = $_POST["provinceState"];

	$cardToken = $_POST["tokenID"];


	// /* Additional connected account info */
	// $firstname = "john";//- get from db
	// $lastname = "smith"; //- get from db
	// $email = null; // get from db
	// $dobDay = $_POST["dobDay"];
	// $dobMonth = $_POST["dobMonth"];
	// $dobYear = $_POST["dobYear"];

	// $userID = 1;

	/* REQUIRED SECURITY INFO BELOW - Billing info for customer and connected account*/
	// $country = "CA"; //ISO 3166-1 alpha-2 country code.
	// $city = "Toronto";
	// $line1 = "55 Colin Ave";
	// $postalCode = "M5P2B8";
	// $provinceState = "ON";

	// /* CARD INFO */
	// $paymentType = "card"; //could be account
	// $expMonth = "11";
	// $expYear = "19";
	// $number = "4000056655665556";
	// $cvc = "111";
	// $nameOnCard = "";
	// $currency = "CAD"; //Three-letter ISO currency code

	/* Additional connected account info */
	// $firstname = "Cameron";//- get from db
	// $lastname = "Connor"; //- get from db
	// $email = null; // get from db
	// $dobDay = "03";
	// $dobMonth = "09";
	// $dobYear = "1997";


	/* SOURCE will be used for connected account external account and customer payment method */

	// $source = [
	// 	  "object" => $paymentType,
	// 	  "exp_month" => $expMonth,
	// 	  "exp_year" => $expYear,
	// 	  "number" => $number,
	// 	  "currency" => $currency,
	// 	  "cvc" => $cvc
	// 	];



	// try {
	// 	$customer = \Stripe\Customer::create(array(
	// 		  "description" => "Customer for ".$firstname." ".$lastname,
	// 		  "email" => $email,
	// 		  "source" => $source // obtained with Stripe.js
	// 		));
	// } catch(\Stripe\Error $e) {
	//   http_response_code(402);
	//   return;
	// }

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

		$acct->external_accounts->create(array("external_account" => $cardToken));

		$result = saveStripeAccount($mysqli, $userID, $acct->id, "card"); //"bank_account" is other option

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