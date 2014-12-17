<?php

function process_credit_card($data) {
		//return "success";
		//$data=array("total"=>$total,"order_id"=>$order_id, "card_number"=>$card_number, "card_cvv"=>$card_cvv, "date"=>$date);
	    $url = 'https://migs.mastercard.com.au/vpcdps';
		$params=array();
		$params['vpc_Version']='1';
		$params['vpc_Command']='pay';	
		///**/**LIVE DETAILS	
		$params['vpc_AccessCode']='4635AD11';
		$params['vpc_Merchant']='CLIPPINCOM01';	

		///**/**TEST DETAILS
		//$params['vpc_AccessCode']='18F907DE';
		//$params['vpc_Merchant']='TESTCLIPPINCOM01';

		$params['vpc_Amount']=$data['total']; //AMOUNT SHOULD BE IN CENTS	
		$params['vpc_MerchTxnRef'] = $data['order_id'];
		$params['vpc_OrderInfo']='DEEPETCH COM ORDER: '.$data['order_id'];
		$params['vpc_CardNum'] = $data['card_number'];
		$params['vpc_CardSecurityCode'] = $data['card_cvv'];
		$params['vpc_CardExp'] = $data['date'];//YYMM
		$params['vpc_CSCLevel']='M';
		$params['vpc_TicketNo']='';					
		
		$postData = "";
		$ampersand = "";
		foreach($params as $key => $value) {
			  if (strlen($value) > 0) {
				$postData .= $ampersand . urlencode($key) . '=' . urlencode($value);
				$ampersand = "&";
			}
		}

		
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec ($ch);



		$message = "";
		if(strchr($response,"<html>") || strchr($response,"<html>")) {			
			$message = $response;
		} else {
			  if (curl_error($ch))
				  $message = "curl_errno=". curl_errno($ch) . "<br/>" . curl_error($ch);
		}
		curl_close ($ch);		
		$map = array();
		if (strlen($message) == 0) {
			$pairArray = explode("&", $response);
			foreach ($pairArray as $pair) {
				$param = explode("=", $pair);
				$map[urldecode($param[0])] = urldecode($param[1]);
			}
			$message         = null2unknown($map, "vpc_Message");
		}
		$merchTxnRef     = $cart['vpc_MerchTxnRef'];					
		$amount          = null2unknown($map, "vpc_Amount");
		$locale          = null2unknown($map, "vpc_Locale");
		$batchNo         = null2unknown($map, "vpc_BatchNo");
		$command         = null2unknown($map, "vpc_Command");
		$version         = null2unknown($map, "vpc_Version");
		$cardType        = null2unknown($map, "vpc_Card");
		$orderInfo       = null2unknown($map, "vpc_OrderInfo");
		$receiptNo       = null2unknown($map, "vpc_ReceiptNo");
		$merchantID      = null2unknown($map, "vpc_Merchant");
		$authorizeID     = null2unknown($map, "vpc_AuthorizeId");
		$transactionNr   = null2unknown($map, "vpc_TransactionNo");
		$acqResponseCode = null2unknown($map, "vpc_AcqResponseCode");
		$txnResponseCode = null2unknown($map, "vpc_TxnResponseCode");				
		
		// CSC Receipt Data
		$cscResultCode   = null2unknown($map, "vpc_CSCResultCode");
		$cscACQRespCode  = null2unknown($map, "vpc_AcqCSCRespCode");					
		
		// AVS Receipt Data
		$avsResultCode   = null2unknown($map, "vpc_AVSResultCode");
		$vACQAVSRespCode = null2unknown($map, "vpc_AcqAVSRespCode");
		$avs_City        = null2unknown($map, "vpc_AVS_City");
		$avs_Country     = null2unknown($map, "vpc_AVS_Country");
		$avs_Street01    = null2unknown($map, "vpc_AVS_Street01");
		$avs_PostCode    = null2unknown($map, "vpc_AVS_PostCode");
		$avs_StateProv   = null2unknown($map, "vpc_AVS_StateProv");
		$avsRequestCode  = null2unknown($map, "vpc_AVSRequestCode");		
		
		$errorTxt = "";
		if(trim($txnResponseCode)!= "0"){
			   $errorTxt = "Error ";
		}

		if(trim($errorTxt)=="") {
			return "success";
		}else{
			return getResponseDescription($txnResponseCode)." [ ".$message ."]" ;
		}
}

////FUNCTIONS////////////////////////////////////////

function getResponseDescription($responseCode) {

    switch ($responseCode) {
        case "0" : $result = "Transaction Successful"; break;
        case "?" : $result = "Transaction status is unknown"; break;
        case "1" : $result = "Unknown Error"; break;
        case "2" : $result = "Bank Declined Transaction"; break;
        case "3" : $result = "No Reply from Bank"; break;
        case "4" : $result = "Expired Card"; break;
        case "5" : $result = "Insufficient funds"; break;
        case "6" : $result = "Error Communicating with Bank"; break;
        case "7" : $result = "Payment Server System Error"; break;
        case "8" : $result = "Transaction Type Not Supported"; break;
        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
        case "A" : $result = "Transaction Aborted"; break;
        case "C" : $result = "Transaction Cancelled"; break;
        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
        case "F" : $result = "3D Secure Authentication failed"; break;
        case "I" : $result = "Card Security Code verification failed"; break;
        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
        case "S" : $result = "Duplicate SessionID (OrderInfo)"; break;
        case "T" : $result = "Address Verification Failed"; break;
        case "U" : $result = "Card Security Code Failed"; break;
        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
        default  : $result = "Unable to be determined"; 
    }
    return $result;
}



function displayAVSResponse($avsResultCode) {
    
    if ($avsResultCode != "") { 
        switch ($avsResultCode) {
            Case "Unsupported" : $result = "AVS not supported or there was no AVS data provided"; break;
            Case "X"  : $result = "Exact match - address and 9 digit ZIP/postal code"; break;
            Case "Y"  : $result = "Exact match - address and 5 digit ZIP/postal code"; break;
            Case "S"  : $result = "Service not supported or address not verified (international transaction)"; break;
            Case "G"  : $result = "Issuer does not participate in AVS (international transaction)"; break;
            Case "A"  : $result = "Address match only"; break;
            Case "W"  : $result = "9 digit ZIP/postal code matched, Address not Matched"; break;
            Case "Z"  : $result = "5 digit ZIP/postal code matched, Address not Matched"; break;
            Case "R"  : $result = "Issuer system is unavailable"; break;
            Case "U"  : $result = "Address unavailable or not verified"; break;
            Case "E"  : $result = "Address and ZIP/postal code not provided"; break;
            Case "N"  : $result = "Address and ZIP/postal code not matched"; break;
            Case "0"  : $result = "AVS not requested"; break;
            default   : $result = "Unable to be determined"; 
        }
    } else {
        $result = "null response";
    }
    return $result;
}


function displayCSCResponse($cscResultCode) {
    
    if ($cscResultCode != "") {
        switch ($cscResultCode) {
            Case "Unsupported" : $result = "CSC not supported or there was no CSC data provided"; break;
            Case "M"  : $result = "Exact code match"; break;
            Case "S"  : $result = "Merchant has indicated that CSC is not present on the card (MOTO situation)"; break;
            Case "P"  : $result = "Code not processed"; break;
            Case "U"  : $result = "Card issuer is not registered and/or certified"; break;
            Case "N"  : $result = "Code invalid or not matched"; break;
            default   : $result = "Unable to be determined"; break;
        }
    } else {
        $result = "null response";
    }
    return $result;
}


function getStatusDescription($statusResponse) {
    if ($statusResponse == "" || $statusResponse == "No Value Returned") {
        $result = "3DS not supported or there was no 3DS data provided";
    } else {
        switch ($statusResponse) {
            Case "Y"  : $result = "The cardholder was successfully authenticated."; break;
            Case "E"  : $result = "The cardholder is not enrolled."; break;
            Case "N"  : $result = "The cardholder was not verified."; break;
            Case "U"  : $result = "The cardholder's Issuer was unable to authenticate due to some system error at the Issuer."; break;
            Case "F"  : $result = "There was an error in the format of the request from the merchant."; break;
            Case "A"  : $result = "Authentication of your Merchant ID and Password to the ACS Directory Failed."; break;
            Case "D"  : $result = "Error communicating with the Directory Server."; break;
            Case "C"  : $result = "The card type is not supported for authentication."; break;
            Case "S"  : $result = "The signature on the response received from the Issuer could not be validated."; break;
            Case "P"  : $result = "Error parsing input from Issuer."; break;
            Case "I"  : $result = "Internal Payment Server system error."; break;
            default   : $result = "Unable to be determined"; break;
        }
    }
    return $result;
}

function null2unknown($map, $key) {
    if (array_key_exists($key, $map)) {
        if (!is_null($map[$key])) {
            return $map[$key];
        }
    } 
    return "No Value Returned";
}   	


function testMigsLib(){
	echo "MIGS included successfully";
}