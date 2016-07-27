<?php
class YandexSMTP{
	public $to = NULL;
	public $subject = NULL;
	public $message = NULL;
	public $from = array('email'=>'info@honeylovers.ru','name'=>'HoneyLovers');
	protected $mail = NULL;
        public $stdTemplate = NULL;
	
	
	function __construct(){
		date_default_timezone_set('Etc/UTC');
		require ROOT.'/lib/PHPMailerAutoload.php';
		$this->mail = new PHPMailer();
	}
	
	/*public function makeMessage($msg) {
		$this->message = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
		<html>
		<head>
		  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		  <title>PHPMailer Test</title>
		</head>
		<body>
		  <div style=\"width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;\">
			".$msg."
		  </div>
		</body>
		</html>";
	}*/
	
        public function makeMessage($msg,$template=NULL){
            if(!file_exists($template)) $template = $this->stdTemplate;
            $tpl = file_get_contents($template);
            if(!is_array($msg)){$msg=array('content'=>$msg);}
            foreach($msg as $msgBitHeader=>$msgBit){
                $tpl = str_replace("{%".$msgBitHeader."%}", $msgBit, $tpl);
            }
            //$tpl = preg_replace("/{%([a-Z]+)%}/","",$tpl); 
            $this->message = $tpl;
        }
        
	public function sendMail(){
		$this->mail->isSMTP();
		$this->mail->SMTPDebug = 0;
		$this->mail->Debugoutput = 'html';
		$this->mail->SMTPSecure = "ssl"; 
		$this->mail->Host = "smtp.yandex.ru";
		$this->mail->Port = 465;
		$this->mail->SMTPAuth = true;
		$this->mail->Username = "info@honeylovers.ru";
		$this->mail->Password = "QU8ZOv82Js6M";
		$this->mail->setFrom($this->from['email'], $this->from['name']);
		$this->mail->addAddress($this->to['email'], $this->to['name']);
		$this->mail->Subject = $this->subject;
		$this->mail->msgHTML($this->message, dirname(__FILE__));
		$this->mail->AltBody = 'This is a plain-text message body';
                $this->mail->CharSet="utf-8";
		// $this->mail->addAttachment('mail/examples/images/phpmailer_mini.png');
		if (!$this->mail->send()) {
			echo "Mailer Error: " . $this->mail->ErrorInfo;
		} else {
			// echo "Message sent!\n";
		}
	}
	
}