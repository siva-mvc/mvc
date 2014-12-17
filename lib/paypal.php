<?php
$environment = 'live'; //sandbox

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */

function PPHttpPost($methodName_, $nvpStr_) {
	global $environment;
	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode('pay_api1.deepetch.com');
	$API_Password = urlencode('N8TPE9PDMAX2M53X');
	$API_Signature = urlencode('Aag3Egh08kq5ykwtKMthVQULlz2YAALqNb9ihJytbJTqliIbz0P6FLXm');

	if($environment == 'sandbox') {
		$API_UserName = urlencode('jforjerald-facilitator_api1.gmail.com');
		$API_Password = urlencode('1386762925');
		$API_Signature = urlencode('AYwPPzsoZrbi3aIIbGIiaTW2mrA8ANmBEkSm1hb0G3dzWlSucLWosU.L');
	}


	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api.sandbox.paypal.com/nvp/";
	}
	$version = urlencode('65.1');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}





function get_paypal_url($data,$item=array()){
	global $environment;
	//$data = array("OrderValue"=>$OrderValue,"Currency"=>$Currency, "PaymentType"=>$PaymentType, "ReturnURL"=>$ReturnURL, "CancelURL"=>$CancelURL);
	$paymentAmount = urlencode($data['OrderValue']);
	$currencyID = urlencode($data['Currency']);
	$paymentType = urlencode($data['PaymentType']);		
	$returnURL = urlencode($data['ReturnURL']);
	$cancelURL = urlencode($data['CancelURL']);
	$nvpStr = "&Amt=$paymentAmount&ReturnUrl=$returnURL&CANCELURL=$cancelURL&PAYMENTACTION=$paymentType&CURRENCYCODE=$currencyID";
	if(count($item)>0){
		$item_name = urlencode($item['name']);
		$item_description = urlencode($item['description']);
		$item_amount = urlencode($item['amount']);
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME0=$item_name&L_PAYMENTREQUEST_0_DESC0=$item_description&L_PAYMENTREQUEST_0_AMT0=$item_amount&&L_PAYMENTREQUEST_0_QTY0=1&&PAYMENTREQUEST_0_ITEMAMT=$item_amount&PAYMENTREQUEST_0_TAXAMT=0.00&PAYMENTREQUEST_0_AMT=$item_amount&ALLOWNOTE=1";
	}
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $nvpStr);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		$token = urldecode($httpParsedResponseAr["TOKEN"]);
		$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
		}
		return $payPalURL;
	} else  {
		//echo $nvpStr;
		//exit;
		return "failed";
	}
}


function post_paypal($data){
	global $environment;
	//$data = array("OrderValue"=>$OrderValue,"Currency"=>$Currency, "PaymentType"=>$PaymentType);
	$payerID = urlencode($_REQUEST['PayerID']);
	$token = urlencode($_REQUEST['token']);
	$paymentType = urlencode($data['PaymentType']);				
	$paymentAmount = urlencode($data['OrderValue']);
	$currencyID = urlencode($data['Currency']);
	$nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentAmount&CURRENCYCODE=$currencyID";
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $nvpStr);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		return "success";
	}else{
		return "failed";
	}

}



?>