<?
##########################
#iManage class: contacts
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class contacts {
	
	// Create new contact
	function createContact($name_first,$name_last,$user_id,$email,$phone_home,$phone_work,$phone_cell,$im,$affiliation,$title,$member) {
		global $databaseConnect;
		global $databaseSelect;
		global $createSuccess;
		global $createResult;
		
		$dbResult = mysql_query('SELECT contacts_id FROM contacts WHERE contacts_email = "'.$email.'" AND contacts_email <> ""',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			$dbResult2 = mysql_query('INSERT INTO contacts (contacts_name_first,contacts_name_last,contacts_email,contacts_phone_home,contacts_phone_work,contacts_phone_cell,contacts_im,contacts_affiliation,contacts_title,contacts_member) VALUES ("'.addslashes($name_first).'","'.addslashes($name_last).'","'.addslashes($email).'","'.addslashes($phone_home).'","'.addslashes($phone_work).'","'.addslashes($phone_cell).'","'.addslashes($im).'","'.addslashes($affiliation).'","'.addslashes($title).'",'.$member.')',$databaseConnect);
			if (mysql_insert_id()) {
					$createSuccess = true;
					$createResult = '';
			}
			else {
				$createSuccess = false;
				$createResult = 'There was a problem creating the contact.';
			}
		}
		else {
			$createSuccess = false;
			$createResult = 'The email address you entered is already in use.';
		}
		
		return $createSuccess;
		return $createResult;
	
	}
	
	// Update existing contact
	function updateContact($id,$name_first,$name_last,$user_id,$email,$phone_home,$phone_work,$phone_cell,$im,$affiliation,$title,$member) {
		global $databaseConnect;
		global $databaseSelect;
		global $updateSuccess;
		global $updateResult;
		
		$dbResult = mysql_query('SELECT contacts_id FROM contacts WHERE contacts_email = "'.$email.'" AND contacts_email <> "" AND contacts_id <> '.$id,$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			$dbResult2 = mysql_query('UPDATE contacts SET contacts_name_first="'.$name_first.'",contacts_name_last="'.$name_last.'",contacts_email="'.$email.'",contacts_phone_home="'.$phone_home.'",contacts_phone_work="'.$phone_work.'",contacts_phone_cell="'.$phone_cell.'",contacts_im="'.$im.'",contacts_affiliation="'.$affiliation.'",contacts_title="'.$title.'",contacts_member='.$member.' WHERE contacts_id = '.$id,$databaseConnect);
			if ($user_id == $_SESSION['IMUserID']) {
				if ($_SESSION['IMUser'] != $name_first) {
					$_SESSION['IMUser'] = $name_first;
				}
			}
			$updateSuccess = true;
			$updateResult = '';
		}
		else {
			$updateSuccess = false;
			$updateResult = 'The email address you entered is already in use.';
		}
		
		return $updateSuccess;
		return $updateResult;
	
	}
	
	// Delete contact
	function deleteContact($id) {
		global $databaseConnect;
		global $databaseSelect;
		global $deleteSuccess;
		
		$dbResult = mysql_query('DELETE FROM contacts WHERE contacts_id = '.$id.' AND contacts_users_id IS NULL',$databaseConnect);
		
		return $deleteSuccess;
	
	}
	
}
?>