<?php
/**
*  
*  Create On 2013-9-29����4:19:48
*  Author lidianbin
*  QQ: 281443751
**/
include("phpmailer/class.phpmailer.php");
include("phpmailer/class.smtp.php");
class MailUtil{
	
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	protected $_debug=0;
	protected $_host=null;
	protected $_port=null;
	protected $_username=null;	
	protected $_password=null;
	protected $_nickname = null;
	
	
	
	public function sedEmail($emailAddress,$title,$content){
		//获取一个外部文件的内容
		$mail = new \PHPMailer();
		$mail->isSMTP();
		$mail->SMTPDebug = $this->_debug;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		//$mail->Host = "smtp.qq.com";
		$mail->CharSet="utf-8";
		$mail->Host = $this->_host;
		
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = $this->_port;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = $this->_username;
		//Password to use for SMTP authentication
		$mail->Password = $this->_password;
		//Set who the message is to be sent from
		$mail->setFrom($this->_username, $this->_nickname);
		//Set an alternative reply-to address
		$mail->addReplyTo($this->_username, $this->_nickname);
		//Set who the message is to be sent to
		$mail->addAddress($emailAddress);
		//Set the subject line
		$mail->Subject = $title;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($content);
		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.gif');
		
		//send the message, check for errors
		if (!$mail->send()) {
		    //echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
		    return true;
		}
		
	}
	
	public function setHost($val){
		$this->_host = $val;
		return $this; 
	}
	
	public function setPort($val){
		$this->_port = $val;
		return $this;
	}
	
	public function setAccount($username,$password,$nickname=''){
		$this->_username=$username;
		$this->_password= $password;
		$this->_nickname=$nickname;
		return $this;
	}
	
	public function Debug($flag){
		if($flag){
			$this->_debug = 2;
		}else{
			$this->_debug = 0;
		}
	}
}

?>