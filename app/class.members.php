<?
##########################
#iManage class: members
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class members {
	
	// Create new membership
	function addMember($database,$uid,$username,$status,$expiration) {
		global $addSuccess;
		global $addResult;
		global $addID;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Generate password
		$password = credentials::generatePassword('8');
		$cryptPassword = credentials::cryptPassword($password);
		// Switch expiration
		$expiration = ($expiration == '') ? 'NULL' : '"'.$expiration.date('H:i:s').'"';
		// Check for existing uid
		$dbResult = mysql_query('SELECT members_id, members_status FROM '.$database.'.members WHERE members_uid = "'.$uid.'"',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			// Create customer record
			$dbResult2 = mysql_query('INSERT INTO '.$database.'.members (members_uid,members_username,members_password,members_status,members_registration_date,members_expiration_date) VALUES ("'.$uid.'","'.addslashes($username).'","'.$cryptPassword.'",'.$status.',"'.date('Y-m-d H:i:s').'",'.$expiration.')',$databaseConnect);
			if (mysql_insert_id()) {
					$addID = mysql_insert_id();
					$addSuccess = true;
					$addResult = $password;
			}
			else {
				$addSuccess = false;
				$addResult = 'Could not add member';
			}
		}
		else {
			// Check existing member
			while ($dbRow = mysql_fetch_array($dbResult)) {
				$members_id = $dbRow['members_id'];
				$members_status = $dbRow['members_status'];
			}
			// Member is active
			if ($members_status == '1') {
				$addSuccess = false;
				$addResult = 'Member already active';
			}
			// Update member
			else {
				$dbResult2 = mysql_query('UPDATE '.$database.'.members SET members_password = "'.$cryptPassword.'",
				members_status = 1,
				members_registration_date = "'.date('Y-m-d H:i:s').'",
				members_expiration_date = NULL WHERE members_id = '.$members_id.'',$databaseConnect);
				$addID = $members_id;
				$addSuccess = true;
				$addResult = $password;
			}
		}
		$databaseClose = database::dbClose();
				
		return $addSuccess;
		return $addResult;
		return $addID;
	}
	
	// Check membership
	function checkMember($database,$uid) {
		global $checkSuccess;
		global $checkID;
		global $checkStatus;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check for existing member
		$dbResult = mysql_query('SELECT members_id, members_status FROM '.$database.'.members WHERE members_uid = "'.$uid.'" LIMIT 1',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		$dbRow = mysql_fetch_array($dbResult);
		if ($dbCount == 1) {
			$checkSuccess = true;
			$checkID = $dbRow['members_id'];
			$checkStatus = $dbRow['members_status'];
		}
		else {
			$checkSuccess = false;
			$checkID = false;
			$checkStatus = false;
		}
		$databaseClose = database::dbClose();
				
		return $checkSuccess;
		return $checkID;
		return $checkStatus;
	}
	
	// Update membership
	function updateMember($database,$id,$password,$status,$expiration) {
		global $updateSuccess;
		global $updateResult;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Switch expiration
		if ($status === '0') {
			$expiration = '"'.date('Y-m-d H:i:s').'"';
		}
		if ($status === '1' && $expiration == '') {
			$expiration = 'NULL';
		}
		$expiration = ($expiration != '' && $expiration != 'NULL') ? '"'.$expiration.date('H:i:s').'"' : '';
		// Encrypt password
		$cryptPassword = ($password != '') ? credentials::cryptPassword($password) : '';
		// Check for existing member
		$dbResult = mysql_query('SELECT members_id FROM '.$database.'.members WHERE members_id = "'.$id.'"',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 1) {
			// Update member record
			$dbQueryVals = '';
			$dbQuery = 'UPDATE '.$database.'.members SET ';
			$dbQueryVals .= ($status != '') ? 'members_status = '.$status.', ' : '';
			$dbQueryVals .= ($password != '') ? 'members_password = "'.$cryptPassword.'", ' : '';
			$dbQueryVals .= ($expiration != '') ? 'members_expiration_date = '.$expiration.', ' : '';
			$dbQuery = $dbQuery.substr($dbQueryVals,0,-2).' WHERE members_id = '.$id;
			$dbResult2 = mysql_query($dbQuery,$databaseConnect);
			$updateSuccess = true;
			$updateResult = 'Member successfully updated';
		}
		else {
			$updateSuccess = false;
			$updateResult = 'Member not found';
		}
		$databaseClose = database::dbClose();
				
		return $updateSuccess;
		return $updateResult;
	}
	
	// Check membership
	function loginMember($database,$username,$password) {
		global $loginSuccess;
		global $loginID;
		global $loginUID;
		global $loginStatus;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check for existing member
		$dbResult = mysql_query('SELECT members_id, members_uid, members_status, members_expiration_date FROM '.$database.'.members WHERE members_username = "'.$username.'" AND members_password = "'.credentials::cryptPassword($password).'" LIMIT 1',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		$dbRow = mysql_fetch_array($dbResult);
		if ($dbCount == 1) {
			$loginID = $dbRow['members_id'];
			$loginUID = $dbRow['members_uid'];
			$loginStatus = $dbRow['members_status'];
			$members_expiration_date = $dbRow['members_expiration_date'];
			// Check for inactive and expired accounts
			if ($loginStatus == '1') {
				if ($members_expiration_date == '' || $members_expiration_date > date('Y-m-d H:i:s')) {
					$loginSuccess = true;
				}
				else {
					// Set expirated accounts to inactive
					$dbResult2 = mysql_query('UPDATE '.$database.'.members SET members_status = 0 WHERE members_id = '.$loginID,$databaseConnect);
					$loginSuccess = false;
				}
			}
			else {
				$loginSuccess = false;
			}
		}
		else {
			$loginSuccess = false;
			$loginID = false;
			$loginUID = false;
			$loginStatus = false;
		}
		$databaseClose = database::dbClose();
				
		return $loginSuccess;
		return $loginID;
		return $loginUID;
		return $loginStatus;
	}
	
	// Cancel membership
	function cancelMember($database,$username,$cancelImmediately,$cancelDate) {
		global $cancelSuccess;
		global $cancelResult;
		global $cancelID;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check for existing member
		$dbResult = mysql_query('SELECT members_id FROM '.$database.'.members WHERE members_username = "'.$username.'"',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			// Member not found
			$cancelSuccess = false;
			$cancelResult = 'Member not found';
		}
		else {
			while ($dbRow = mysql_fetch_array($dbResult)) {
				$members_id = $dbRow['members_id'];
			}
			// Set cancel date
			$membership_active = '0';
			$membership_expiration_date = date('Y-m-d');
			if ($cancelImmediately != 'y' && $cancelDate > date('Y-m-d')) {
				$membership_active = '1';
				$membership_expiration_date = $cancelDate;
			}
			// Update member
			$dbResult2 = mysql_query('UPDATE '.$database.'.members SET members_status = '.$membership_active.',
			members_expiration_date = "'.$membership_expiration_date.'" WHERE members_id = '.$members_id.'',$databaseConnect);
			$cancelID = $members_id;
			$cancelSuccess = true;
			$cancelResult = 'Membership cancelled';
		}
		$databaseClose = database::dbClose();
				
		return $cancelSuccess;
		return $cancelResult;
		return $cancelID;
	}
	
}
?>