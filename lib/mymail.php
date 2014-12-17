<?php
	
	function send_smtp_mail($from,$to,$subject,$body){
			require_once('php_mailer/class.phpmailer.php');
			$mail = new PHPMailer();
			$mail->IsSMTP();         			                                        
			$mail->SMTPAuth   = true;                  
			$mail->Host       = "smtp.sendgrid.net"; 
			$mail->Port       = 25;                    
			$mail->Username   = "deepetch"; 
			$mail->Password   = "deep@123";
			$mail->SetFrom($from, "Deepetch");			
			$mail->AddReplyTo($from, "Deepetch");
			$mail->Subject    = $subject;
			$mail->MsgHTML($body);
			$mail->AddAddress($to);
			//$mail->AddBCC("admin@deepetch.com");
			if(!$mail->Send()) {
			  //echo "Mailer Error: " . $mail->ErrorInfo;
				return false;
			} else {
				return true;
			  //echo "Message sent!";
			}
	}
	

	class MyMail{
		public $from, $to, $bcc ,$cc, $subject, $body, $type, $header;
		private $send_from, $temp_subject, $temp_body;

		public function __construct($to="",$subject="",$body="", $cc="", $bcc="", $from="", $type="" ){
			$this->send_from = "support@deepetch.com";
			$this->to = trim($to);
			$this->subject = trim($subject);
			$this->body = trim($body);
			$this->type = empty($type) ? "default" : trim($type);
			$this->cc = trim($cc);
			$this->bcc = trim($bcc);
			$this->from = trim($from);
			$this->temp_subject =  $this->subject;
			$this->temp_body =  $this->body;
		}

		public function setTemplate($temp){
			$file_path = MAIL_DIR.DS.$temp.".html";
			if(file_exists($file_path)){
				$content = file_get_contents($file_path);
				$body = explode("==========", $content);
				$this->temp_subject = isset($body[0]) ? trim($body[0]) : "2NO TEMPLATE FOUND [$temp] ";
				$this->temp_body = isset($body[1]) ? trim($body[1]) : "2NO TEMPLATE FOUND [$temp] ";
			}else{
				$this->temp_subject = "1NO TEMPLATE FOUND [$temp] ";
				$this->temp_body = "1NO TEMPLATE FOUND [$temp] ";
			}
		}


		public function merge($fields){
			$this->subject =  $this->temp_subject;
			$this->body =  $this->temp_body;
			foreach($fields as $key=> $value){
				$this->subject = str_replace("*|".strtoupper($key)."|*", $value, $this->subject);
				$this->body = str_replace("*|".strtoupper($key)."|*", $value, $this->body);
			}
		}


		public function send(){
			require(MAIL_DIR."/_template.php");
			//global $MailBegin, $MailEnd;
			if($this->valid()){
				$this->build_header();
				$this->body = $MailBegin.$this->body.$MailEnd;
				$body_copy = $this->body;
				mail("developer@noelnetwork.com",$this->subject,$body_copy,$this->header,'-f'.$this->send_from);
				if($this->to == "support@deepetch.com"){
					$this->send_from = "admin@deepetch.com";
				}
				return send_smtp_mail($this->send_from,$this->to,$this->subject,$this->body); 
			}else{
				return 0;
			}
		}


		private function valid(){
			return empty($this->to) || empty($this->subject) || empty($this->body) ? 0 : 1;
		}


		
		private function build_header(){
			$this->header  = 'MIME-Version: 1.0' . "\r\n";
			$this->header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$this->header .= empty($this->from) ? "From: Deepetch <support@deepetch.com>" : "From: ".trim($this->from);
			$this->header .= "\r\n".'Reply-To: support@deepetch.com';
			$this->header .= empty($this->cc) ? "\r\n".'Cc: Deepetch <admin@deepetch.com>'  : "\r\n".'Cc: '. trim($this->cc);
			$this->header .= empty($this->bcc) ? '' : "\r\n".'Bcc: '. trim($this->bcc);
		}



	}

?>