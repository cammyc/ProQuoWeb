<?php
	try {
	  $customer_id = "..."; // Load the Stripe Customer ID for your logged in user
	  $customer = \Stripe\Customer::retrieve($customer_id);
	  header('Content-Type: application/json');
	  echo $customer->jsonSerialize();
	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	}
?>