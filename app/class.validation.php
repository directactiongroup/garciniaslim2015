<?
##########################
#iManage class: validation
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class validation {
	
	// Validate email address
	function validateEmail($email) {

		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		}
		else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			}
			else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			}
			else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/',$domain)) {
				// character not valid in domain part
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))) {
				// character not valid in local part unless local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))) {
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		// Return valid
		if ($isValid == true) {
			return true;
		}
		else {
			return false;	
		}
		
	}
	
	// Check string for substring or array value
	function searchString($haystack,$needle) {
		global $checkSuccess;
		
		$checkSuccess = false;
		if (is_array($needle)) {
			foreach ($needle as $val) {
				if (strstr($haystack,$val)) {
					$checkSuccess = true;
					break;
				}
			}
		}
		else {
			$checkSuccess = (strstr($haystack,$needle)) ? true : false;
		}
		
		return $checkSuccess;	
		
	}
	
	// Convert phone number to integer
	function htmlTextInsert($htmlTextStr) {
		
		return nl2br(preg_replace('/[^\x00-\x7f]/','',$htmlTextStr));
		
	}
	
	// Convert phone number to integer
	function htmlTextDisplay($htmlTextStr) {
		
		return str_replace('<br />','',stripslashes($htmlTextStr));
		
	}
	
	// Convert phone number to integer
	function phoneToInt($phone) {
		
		return ereg_replace('[^0-9]+', '', $phone);
		
	}
	
	// Convert integer to phone number
	function intToPhone($phone) {
		
		if (strlen($phone) == 7) {
			$pos1 = substr($phone,0,3);
			$pos2 = substr($phone,3,4);
			$phone = $pos1.'.'.$pos2;
		}
		else if (strlen($phone) == 10) {
			$pos1 = substr($phone,0,3);
			$pos2 = substr($phone,3,3);
			$pos3 = substr($phone,6,4);
			$phone = $pos1.'.'.$pos2.'.'.$pos3;
		}
		else if (strlen($phone) == 11) {
			$pos1 = substr($phone,0,1);
			$pos2 = substr($phone,1,3);
			$pos3 = substr($phone,4,3);
			$pos4 = substr($phone,7,4);
			$phone = $pos1.'.'.$pos2.'.'.$pos3.'.'.$pos4;
		}
		
		return $phone;
		
	}
	
	// Truncate string
	function stringTrunc($tString,$tLengthMin,$tLengthMax,$contString) {
		
		if (strlen($tString) > $tLengthMax) {
			$tString = substr($tString,0,$tLengthMax);
			$tEndChar = array('"','!','?','.',' ');
			$tEndPos = array();
			if (strlen($tString) > $tLengthMin) {
				foreach ($tEndChar as $val) {
					$tEndPos[] = strrpos($tString,$val);
				}
				$tPos = max($tEndPos);
			}
			else {
				$tPos = $tLengthMin;	
			}
			$tString = substr($tString,0,$tPos).$contString;
		}
		
		return $tString;
		
	}
	
}
?>