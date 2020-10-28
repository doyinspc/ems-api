<?php

class sms{
	var $fors;
	var $to;
	var	$from;
	var $subject;
	var $message;
	var $by;
	function sender(){
		if($isset($this->from)){
			$from_address = $this->from;
			}
		else{
			$from_address = 'DEFAULT NAME';
			}
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'Content-Transfer-Encoding: 7bit';
			$headers[] = 'From: ' . $from_address;
			
			$others = null;
			$success = mail($this->to, $this->subject, $this->message, $headers, $others);
			if($success){
				$a = "`id` = NULL";
				$a .= "`fors` = ".$this->fors; 
				$a .= " ,`to_address` = ".$this->to; 
				$a .= " ,`from_address` = ".$this->from;
				$a .= " ,`subject` = ".$this->subject;
				$a .= " ,`message` = ".$this->message;
				$a .= " ,`headers` = ".serialize($headers);
				$a .= " ,`others` = ".serialize($others);
				
				$con = new Connect;
				$sth = $con->insert('sent_mesages_db', $a); 
				$con = NULL;
				}
			}
	function success(){
		
		
		}
		
}

?>