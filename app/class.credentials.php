<?
##########################
#iManage class: credentials
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class credentials {
	
	// Create encrypted passwords
	function generatePassword($passwordLength) {
		global $passGen;
		
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $passwordLength; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		$passGen = implode($pass); 
		
		return $passGen;
	}
	
	// Encrypt a password
	function cryptPassword($password) {
		global $saltStr;
		global $passCrypt;
		
		$passCrypt = md5(md5($saltStr).$password);
		
		return $passCrypt;
	}
	
	// Encrypt a password
	function resetPasswordRequest($password,$email) {
		global $saltStr;
		global $resetResult;
		
		$resetResult = $this->cryptPassword($password).'.'.md5($email);
		
		return $resetResult;
	}
	
	// Check password reset string
	function resetPasswordCheck($type,$database,$resetStr) {
		global $saltStr;
		global $checkResetResult;
		global $checkID;
		global $checkEmail;
		
		$arrReset = explode('.',$resetStr);
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		switch ($type) {
			case 'user':
				$dbResult = mysql_query('SELECT a.users_id AS id, a.users_password AS password, b.contacts_email AS email FROM '.$database.'.users a LEFT JOIN '.$database.'.contacts b ON a.users_id = b.contacts_users_id WHERE MD5(b.contacts_email) = "'.$arrReset[1].'" AND a.users_active = 1',$databaseConnect);
			break;
			case 'member':
				$dbResult = mysql_query('SELECT members_id AS id, members_password AS password, members_username AS email FROM '.$database.'.members WHERE MD5(members_username) = "'.$arrReset[1].'" AND members_status = 1',$databaseConnect);
			break;
		}
		$dbRow = mysql_fetch_array($dbResult);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 1 && $this->cryptPassword($dbRow['password']) == $arrReset[0]) {
			$checkResetResult = true;
			$checkID = $dbRow['id'];
			$checkEmail = $dbRow['email'];
		}
		else {
			$checkResetResult = false;
		}
		$databaseClose = database::dbClose();
		
		return $checkResetResult;
		return $checkID;
		return $checkEmail;
	}

}
?>