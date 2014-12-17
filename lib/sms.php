<?php
	
function send_sms($to,$msg){
		require_once('sms/Twilio.php');
		$from = '+12107624393';
		$sid = "ACd7a7afe0fe559490d34118c4d11f95b6"; 
		$token = "063a59cb4020b8dde4e38a2b17c1f930"; 
		$result = "";
		$client = new Services_Twilio($sid, $token);
		try {
			$message = $client->account->messages->sendMessage(
			  $from, 
			  $to,
			  $msg
			);
		}catch (Exception  $e) {
    	 	$result = $e->getMessage();
		}
		if(!empty($result)){
			return $result;
		}else{
			return "SUCCESS";
		}
		//return $message->sid;
	}


?>