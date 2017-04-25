<?php
	$customer_id = "..."; // Load the Stripe Customer ID for your logged in user

	try {
	  $customer = \Stripe\Customer::retrieve($customer_id);
	  $customer->sources->create(array("source" => $_POST["source"]));
	  http_response_code(200);
	} catch(\Stripe\Error $e) {
	  http_response_code(402);
	}
?>