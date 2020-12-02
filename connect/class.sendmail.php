<?php
class SimpleMail
{
	// class properties- parts of a message
	private $sentBy;
	private $sentTo;
	private $toAddress;
	private $CCAddress;
	private $BCCAddress;
	private $fromAddress;
	private $subject;
	private $sendText;
	private $textBody;
	private $sendHTML;
	private $HTMLBody;
	
// initialize the message parts with blank or default values
	public function __construct() {
	$this->	sentBy = '';
	$this->	sentTo = '';
	$this->toAddress = '';
	$this->CCAddress = '';
	$this->BCCAddress = '';
	$this->fromAddress = '';
	$this->subject = '';
	$this->sendText = true;
	$this->textBody = '';
	$this->sendHTML = false;
	$this->HTMLBody = '';
	
	}
	// set whether to send By which staff or office as text
	public function setSendBy($value) {
	$this->sendBy = $value;
	}
	// set whether to send to which staff or office as text
	public function setSendTo($value) {
	$this->sendTo = $value;
	}
	// set TO address
	public function setToAddress($value) {
	$this->toAddress = $value;
	}
	// set CC address
	public function setCCAddress($value) {
	$this->CCAddress = $value;
	}
	// set BCC address
	public function setBCCAddress($value) {
	$this->BCCAddress = $value;
	}
	// set FROM address
	public function setFromAddress($value) {
	$this->fromAddress = $value;
	}
	// set message subject
	public function setSubject($value) {
	$this->subject = $value;
	}
	// set whether to send email as text
	public function setSendText($value) {
	$this->sendText = $value;
	}
	// set text email message body
	public function setTextBody($value) {
	$this->sendText = true;
	$this->textBody = $value;
	}
	// set whether to send email as HTML
	public function setSendHTML($value) {
	$this->sendHTML = $value;
	}
	// set text HTML message body
	public function setHTMLBody($value) {
	$this->sendHTML = true;
	$this->HTMLBody = $value;
	}
	// send email
	public function send($to = null, $subject = null, $message = null, $headers = null) 
	{
		$success = false;
		if (!is_null($to) && !is_null($subject) && !is_null($message)) 
		{
			$success = mail($to, $subject, $message, $headers);
			return $success;
		} 
		else {
		$headers = array();
		if (!empty($this->fromAddress)) {
		$headers[] = 'From: ' . $this->fromAddress;
		}
		if (!empty($this->CCAddress)) {
		$headers[] = 'CC: ' . $this->CCAddress;
		}
		if (!empty($this->BCCAddress)) {
		$headers[] = 'BCC: ' . $this->BCCAddress;
		}
		if ($this->sendText && !$this->sendHTML) {
		$message = $this->textBody;
		} 
		elseif (!$this->sendText && $this->sendHTML) 
		{
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
			$headers[] = 'Content-Transfer-Encoding: 7bit';
			$message = $this->HTMLBody;
		} 
	elseif ($this->sendText && $this->sendHTML) {
		$boundary = '==MP_Bound_xyccr948x==';
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: multipart/alternative; boundary="'. $boundary . '"';
		$message = 'This is a Multipart Message in MIME format.' . "\n";
		$message .= '--' . $boundary . "\n";
		$message .= 'Content-type: text/plain; charset="iso-8859-1"' ."\n";
		$message .= 'Content-Transfer-Encoding: 7bit' . "\n\n";
		$message .= $this->textBody . "\n";
		$message .= '--' . $boundary . "\n";
		$message .= 'Content-type: text/html; charset="iso-8859-1"'. "\n";
		$message .= 'Content-Transfer-Encoding: 7bit' . "\n\n";
		$message .= $this->HTMLBody . "\n";
		$message .= '--' . $boundary . '--';
	}
	$success = mail($this->toAddress, $this->subject, $message, join("\r\n", $headers));
	return $success;
	}
	}
}


?>