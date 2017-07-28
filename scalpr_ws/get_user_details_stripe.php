<?php
	include_once("databasehelper_dev.php");
	require_once 'vendor/autoload.php';
	\Stripe\Stripe::setApiKey("sk_test_dQa2CvZAK2mnz9htGXiAyo4w");

	// $userID = Security::authenticateToken($_SERVER['HTTP_SCALPRVERIFICATION']);


	$userID = 1;

	
		$mysqli = getDB();

		$data = getUserDetails($mysqli, $userID); //get profile and account details

		$stripeDetails = getStripeAccount($mysqli, $userID); //get stripe specific details

		if(!empty($stripeDetails->connectID)){ //check if user has a registered fund receival method
			$array = \Stripe\Account::retrieve($stripeDetails->connectID)->external_accounts->all(array(
	  		'limit'=>1, 'object' => $stripeDetails->paymentType));

		  	if (strcasecmp($stripeDetails->receivalType, "card") == 0){
		  		$stripeDetails->receivalPreview = $array->data[0]->last4; //if card preview is last 4 numbers
		  	}else{
		  		
		  	}
		}

		if(!empty($stripeDetails->customerID)){ //payment
			$cus = \Stripe\Customer::retrieve($stripeDetails->customerID);// check if user has a registered payment method

			if (strcasecmp($stripeDetails->paymentType, "card") == 0){
				$stripeDetails->paymentPreview = $array->data[0]->last4; //if card preview is last 4 numbers
			}else{

			}
		}

		if($data != null){
			if($stripeDetails != false){
			  	$data->stripeAccount = $stripeDetails;
			}else{
				$data->stripeAccount = "0";
			}

			echo json_encode($data);
		}else{
			echo "0";
		}

		$mysqli->close();
	
?>