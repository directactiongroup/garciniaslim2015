<?
##########################
#iManage class: users
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################
class users {
	
	// Create encrypted passwords
	function makePassword($password) {
		global $saltStr;
		global $passCrypt;
		
		$passCrypt = md5($saltStr.$password);
		
		return $passCrypt;
	}

	// Check is user is valid
	function checkUser($username,$password) {
		global $databaseConnect;
		global $databaseSelect;
		global $dbResult;
		global $dbCount;
		global $dbRow;
		
		$passCrypt = $this->makePassword($password);
		$dbResult = mysql_query('SELECT a.users_id, a.users_security, a.users_active, b.contacts_name_first FROM users a LEFT JOIN contacts b ON a.users_id = b.contacts_users_id WHERE a.users_username = "'.addslashes($username).'" AND a.users_password = "'.$passCrypt.'"',$databaseConnect);
		$dbRow = mysql_fetch_array($dbResult);
		$dbCount = mysql_num_rows($dbResult);
		
		return $dbCount;
		return $dbRow;
	}
	
	// Generate encrypted password/email string for password retrieval
	function resetPasswordRequest($email) {
		global $databaseConnect;
		global $databaseSelect;
		global $dbResult;
		global $dbCount;
		global $dbRow;
		global $resetResult;
		
		$dbResult = mysql_query('SELECT a.users_password FROM users a LEFT JOIN contacts b ON a.users_id = b.contacts_users_id WHERE b.contacts_email = "'.addslashes($email).'" AND a.users_active = 1',$databaseConnect);
		$dbRow = mysql_fetch_array($dbResult);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 1) {
			$resetResult = $this->makePassword($dbRow['users_password']).'.'.md5($email);
		}
		else {
			$resetResult = false;
		}
		
		return $resetResult;
	
	}

	// Check password reset request
	function checkPasswordRequest($reset) {
		global $databaseConnect;
		global $databaseSelect;
		global $dbResult;
		global $dbRow;
		global $checkResetResult;
		global $saltStr;
	
		$resetStr = explode('.',$reset);
		$dbResult = mysql_query('SELECT a.users_id, a.users_username, a.users_password, b.contacts_email FROM users a LEFT JOIN contacts b ON a.users_id = b.contacts_users_id WHERE MD5(b.contacts_email) = "'.$resetStr[1].'" AND a.users_active = 1',$databaseConnect);
		$dbRow = mysql_fetch_array($dbResult);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 1 && $this->makePassword($dbRow['users_password']) == $resetStr[0]) {
			$checkResetResult = true;
			
		}
		else {
			$checkResetResult = false;
		}
		
		return $checkResetResult;
		return $dbRow;
	}
	
	// Update user password
	function updateUserPassword($reset,$password) {
		global $databaseConnect;
		global $databaseSelect;
		global $dbResult;
		global $dbRow;
		global $updateResult;
		
		$this->checkPasswordRequest($reset);
		$users_id = $dbRow['users_id'];
		$dbResult = mysql_query('UPDATE users SET users_password = "'.$this->makePassword($password).'" WHERE users_id = '.$users_id,$databaseConnect);
				
		return $updateResult;
		return $dbRow;
	}
	
	// Create new user
	function createUser($username,$password,$security,$active,$name_first,$name_last,$email,$contacts_id) {
		global $databaseConnect;
		global $databaseSelect;
		global $createSuccess;
		global $createResult;
		
		if ($contacts_id != '') {
			$contactsQuery1 = 'SELECT contacts_id FROM contacts WHERE contacts_email = "'.$email.'" AND contacts_id <> '.$contacts_id;
		}
		else {
			$contactsQuery1 = 'SELECT contacts_id FROM contacts WHERE contacts_email = "'.$email.'"';
		}
		$dbResult = mysql_query($contactsQuery1,$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			$dbResult2 = mysql_query('SELECT users_id FROM users WHERE users_username = "'.$username.'"',$databaseConnect);
			$dbCount2 = mysql_num_rows($dbResult2);
			if ($dbCount2 == 0) {
				$passCrypt = $this->makePassword($password);
				$dbResult3 = mysql_query('INSERT INTO users (users_username,users_password,users_security,users_active) VALUES ("'.addslashes($username).'","'.$passCrypt.'",'.$security.','.$active.')',$databaseConnect);
				if (mysql_insert_id()) {
					if ($contacts_id != '') {
						$dbResult4 = mysql_query('UPDATE contacts SET contacts_name_first="'.addslashes($name_first).'",contacts_name_last="'.addslashes($name_last).'",contacts_email="'.addslashes($email).'",contacts_users_id='.mysql_insert_id().' WHERE contacts_id = '.$contacts_id,$databaseConnect);
						$createSuccess = true;
						$createResult = '';
					}
					else {
						$dbResult4 = mysql_query('INSERT INTO contacts (contacts_name_first,contacts_name_last,contacts_email,contacts_users_id,contacts_member) VALUES ("'.addslashes($name_first).'","'.addslashes($name_last).'","'.addslashes($email).'",'.mysql_insert_id().',1)',$databaseConnect);
						if (mysql_insert_id()) {
							$createSuccess = true;
							$createResult = '';
						}
						else {
							$createSuccess = false;
							$createResult = 'There was a problem creating the user.';
						}
					}
				}
				else {
					$createSuccess = false;
					$createResult = 'There was a problem creating the user.';
				}
			}
			else {
				$createSuccess = false;
				$createResult = 'The username you entered is already in use.';
			}
		}
		else {
			$createSuccess = false;
			$createResult = 'The email address you entered is already in use.';
		}
		
		return $createSuccess;
		return $createResult;
	
	}
	
	// Update existing user
	function updateUser($id,$username,$password,$security,$active,$name_first,$name_last,$email) {
		global $databaseConnect;
		global $databaseSelect;
		global $updateSuccess;
		global $updateResult;
		
		$dbResult = mysql_query('SELECT contacts_id FROM contacts WHERE contacts_email = "'.$email.'" AND contacts_users_id <> '.$id,$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			$dbResult2 = mysql_query('SELECT users_id FROM users WHERE users_username = "'.$username.'"  AND users_id <> '.$id,$databaseConnect);
			$dbCount2 = mysql_num_rows($dbResult2);
			if ($dbCount2 == 0) {
				$dbResult3 = mysql_query('SELECT users_id FROM users WHERE users_password = "'.$password.'"  AND users_id <> '.$id,$databaseConnect);
				$dbCount3 = mysql_num_rows($dbResult3);
				if ($dbCount3 == 0) {
					$password = $this->makePassword($password);
				}
				if ($_SESSION['Security'] > 1) {
					$dbResult3 = mysql_query('UPDATE users SET users_username="'.addslashes($username).'",users_password="'.$password.'" WHERE users_id = '.$id,$databaseConnect);
				}
				else {
					$dbResult3 = mysql_query('UPDATE users SET users_username="'.addslashes($username).'",users_password="'.$password.'",users_security='.$security.',users_active='.$active.' WHERE users_id = '.$id,$databaseConnect);
				}
				$dbResult3 = mysql_query('UPDATE contacts SET contacts_name_first="'.addslashes($name_first).'",contacts_name_last="'.addslashes($name_last).'",contacts_email="'.addslashes($email).'" WHERE contacts_users_id = '.$id,$databaseConnect);
				if ($id == $_SESSION['UserID']) {
					if ($_SESSION['User'] != $name_first) {
						$_SESSION['User'] = $name_first;
					}
					if ($_SESSION['Security'] != $security && $_SESSION['Security'] <= 1) {
						$_SESSION['Security'] = $security;
					}
				}
				$updateSuccess = true;
				$updateResult = '';
			}
			else {
				$updateSuccess = false;
				$updateResult = 'The username you entered is already in use.';
			}
		}
		else {
			$updateSuccess = false;
			$updateResult = 'The email address you entered is already in use.';
		}
		
		return $updateSuccess;
		return $updateResult;
	
	}
	
	// Delete user
	function deleteUser($id) {
		global $databaseConnect;
		global $databaseSelect;
		global $deleteSuccess;
		
		if ($id != $_SESSION['UserID']) {
			$dbResult = mysql_query('DELETE FROM users WHERE users_id = '.$id,$databaseConnect);
			$dbResult = mysql_query('UPDATE contacts SET contacts_users_id = NULL WHERE contacts_users_id = '.$id,$databaseConnect);
		}
		
		return $deleteSuccess;
	
	}
	
}
?>