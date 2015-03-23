<?
##########################
#iManage class: customers
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class customers {
	
	// Create new customer
	function createCustomer($name_first,$name_middle,$name_last,$email,$billing_address,$billing_address2,$billing_city,$billing_state,$billing_zip,$billing_country,$billing_phone,$shipping_name_first,$shipping_name_last,$shipping_address,$shipping_address2,$shipping_city,$shipping_state,$shipping_zip,$shipping_country,$shipping_phone) {
		global $createSuccess;
		global $createResult;
		global $createID;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check for existing email
		$dbResult = mysql_query('SELECT customers_id FROM customers WHERE customers_email = "'.$email.'"',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			// Create customer record
			$customers_uid = $this->createCustomerUID($name_first,$name_last,$email);
			$dbResult2 = mysql_query('INSERT INTO customers (customers_name_first,customers_name_middle,customers_name_last,customers_email,customers_uid,customers_created_datetime) VALUES ("'.addslashes($name_first).'","'.addslashes($name_middle).'","'.addslashes($name_last).'","'.strtolower($email).'","'.$customers_uid.'","'.date('Y-m-d H:i:s').'")',$databaseConnect);
			if (mysql_insert_id()) {
					$createID = mysql_insert_id();
					// Create customer billing address
					if (!empty($billing_address) || !empty($billing_city) || !empty($billing_state) || !empty($billing_zip) || !empty($billing_country) || !empty($billing_phone)) {
						$dbResult2 = mysql_query('INSERT INTO customers_address (customers_address_id,customers_address_customers_id,customers_address_type,customers_address_name_first,customers_address_name_last,customers_address_address_1,customers_address_address_2,customers_address_city,customers_address_state,customers_address_zip,customers_address_country,customers_address_phone) VALUES (customers_address_id,'.$createID.',1,"'.addslashes($name_first).'","'.addslashes($name_last).'","'.addslashes($billing_address).'","'.addslashes($billing_address2).'","'.addslashes($billing_city).'","'.addslashes($billing_state).'","'.addslashes($billing_zip).'","'.addslashes($billing_country).'","'.addslashes($billing_phone).'")',$databaseConnect);
					}
					// Create customer shipping address
					if (!empty($shipping_name_first) || !empty($shipping_name_last) || !empty($shipping_address) || !empty($shipping_city) || !empty($shipping_state) || !empty($shipping_zip) || !empty($shipping_country)) {
						$dbResult2 = mysql_query('INSERT INTO customers_address (customers_address_id,customers_address_customers_id,customers_address_type,customers_address_name_first,customers_address_name_last,customers_address_address_1,customers_address_address_2,customers_address_city,customers_address_state,customers_address_zip,customers_address_country,customers_address_phone) VALUES (customers_address_id,'.$createID.',2,"'.addslashes($shipping_name_first).'","'.addslashes($shipping_name_last).'","'.addslashes($shipping_address).'","'.addslashes($shipping_address2).'","'.addslashes($shipping_city).'","'.addslashes($shipping_state).'","'.addslashes($shipping_zip).'","'.addslashes($shipping_country).'","'.addslashes($shipping_phone).'")',$databaseConnect);
					}
					$createSuccess = true;
					$createResult = $customers_uid;
			}
			else {
				$createSuccess = false;
				$createResult = 'Could not create customer';
			}
		}
		else {
			$createSuccess = false;
			$createResult = 'Customer email already exists';
		}
		$databaseClose = database::dbClose();
				
		return $createSuccess;
		return $createResult;
		return $createID;
	}
	
	// Create uid
	function createCustomerUID($name_first,$name_last,$email) {
		global $customerUID;
		
		$customerUID = md5($email.$name_first.$name_last.date('Y-m-d H:i:s'));
	
		return $customerUID;
	}
	
	// Update customer address
	function checkCustomerExists($email,$name_first,$name_last,$address,$city,$state,$zip,$country) {
		global $checkSuccess;
		global $checkResult;
		global $checkEmail;
		
		$checkSuccess = false;
		$checkResult = false;
		$checkEmail = false;
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check for existing email
		if (!empty($email)) {
			$dbResult = mysql_query('SELECT customers_id, customers_uid FROM customers WHERE LOWER(customers_email) = "'.strtolower($email).'"',$databaseConnect);
			$dbCount = mysql_num_rows($dbResult);
			if ($dbCount != 0) {
				while ($dbRow = mysql_fetch_array($dbResult)) {
					$checkCustomerUID = $dbRow['customers_uid'];
				}
				$checkSuccess = true;
				$checkResult = $checkCustomerUID;
				$checkEmail = true;
				// Check for existing name
				if (!empty($name_first) && !empty($name_last)) {
					$dbResult2 = mysql_query('SELECT customers_id FROM customers WHERE LOWER(customers_name_first) = "'.strtolower($name_first).'" AND LOWER(customers_name_last) = "'.strtolower($name_last).'" AND LOWER(customers_email) = "'.strtolower($email).'"',$databaseConnect);
					$dbCount2 = mysql_num_rows($dbResult2);
					if ($dbCount2 != 0) {
						while ($dbRow2 = mysql_fetch_array($dbResult2)) {
							$checkCustomerID = $dbRow2['customers_id'];
						}
						$checkSuccess = true;
						$checkResult = $checkCustomerUID;
						// Check for existing address
						if (!empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($country)) {
							$dbResult3 = mysql_query('SELECT customers_address_id FROM customers_address WHERE LOWER(customers_address_address_1) = "'.strtolower($address).'" AND LOWER(customers_address_city) = "'.strtolower($city).'" AND LOWER(customers_address_state) = "'.strtolower($state).'" AND LOWER(customers_address_zip) = "'.strtolower($zip).'" AND LOWER(customers_address_country) = "'.strtolower($country).'" AND customers_address_customers_id = '.$checkCustomerID,$databaseConnect);
							$dbCount3 = mysql_num_rows($dbResult3);
							if ($dbCount3 != 0) {
								$checkSuccess = true;
								$checkResult = $checkCustomerUID;								
							}
							else {
								$checkSuccess = false;
								$checkResult = false;
							}
						}
					}
					else {
						$checkSuccess = false;
						$checkResult = false;
					}
				}
			}
		}
		$databaseClose = database::dbClose();
		
		return $checkSuccess;
		return $checkResult;
		return $checkEmail;
	}
	
	// Check customer
	function getCustomer($uid) {
		global $getSuccess;
		global $getCustomerID;
		global $getCustomerNameFirst;
		global $getCustomerNameLast;
		global $getCustomerEmail;
		global $getCustomerCreated;
		global $getCustomerBillingID;
		global $getCustomerBillingNameFirst;
		global $getCustomerBillingNameLast;
		global $getCustomerBillingAddress;
		global $getCustomerBillingAddress2;
		global $getCustomerBillingCity;
		global $getCustomerBillingState;
		global $getCustomerBillingZip;
		global $getCustomerBillingCountry;
		global $getCustomerBillingPhone;
		global $getCustomerShippingArray;
	
		$getCustomerShippingArray = array();
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Get customer record
		$dbResult = mysql_query('SELECT * FROM customers WHERE customers_uid = "'.$uid.'" LIMIT 1',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 1) {
			while ($dbRow = mysql_fetch_array($dbResult)) {
				$getCustomerID = $dbRow['customers_id'];
				$getCustomerNameFirst = $dbRow['customers_name_first'];
				$getCustomerNameLast = $dbRow['customers_name_last'];
				$getCustomerEmail = $dbRow['customers_email'];
				$getCustomerCreated = $dbRow['customers_created_datetime'];
			}
			// Get billing address
			$dbResult2 = mysql_query('SELECT * FROM customers_address WHERE customers_address_customers_id = '.$getCustomerID.' AND customers_address_type = 1 LIMIT 1',$databaseConnect);
			while ($dbRow = mysql_fetch_array($dbResult2)) {
				$getCustomerBillingID = $dbRow['customers_address_id'];
				$getCustomerBillingNameFirst = $dbRow['customers_address_name_first'];
				$getCustomerBillingNameLast = $dbRow['customers_address_name_last'];
				$getCustomerBillingAddress = $dbRow['customers_address_address_1'];
				$getCustomerBillingAddress2 = $dbRow['customers_address_address_2'];
				$getCustomerBillingCity = $dbRow['customers_address_city'];
				$getCustomerBillingState = $dbRow['customers_address_state'];
				$getCustomerBillingZip = $dbRow['customers_address_zip'];
				$getCustomerBillingCountry = $dbRow['customers_address_country'];
				$getCustomerBillingPhone = $dbRow['customers_address_phone'];
			}
			// Get shipping address array
			$dbResult2 = mysql_query('SELECT * FROM customers_address WHERE customers_address_customers_id = '.$getCustomerID.' AND customers_address_type = 2',$databaseConnect);
			while ($dbRow = mysql_fetch_array($dbResult2)) {
				$arrShipping[] = $dbRow['customers_address_id'];
				$arrShipping[] = $dbRow['customers_address_name_first'];
				$arrShipping[] = $dbRow['customers_address_name_last'];
				$arrShipping[] = $dbRow['customers_address_address_1'];
				$arrShipping[] = $dbRow['customers_address_address_2'];
				$arrShipping[] = $dbRow['customers_address_city'];
				$arrShipping[] = $dbRow['customers_address_state'];
				$arrShipping[] = $dbRow['customers_address_zip'];
				$arrShipping[] = $dbRow['customers_address_country'];
				$arrShipping[] = $dbRow['customers_address_phone'];
				$getCustomerShippingArray[] = $arrShipping;
			}
			$getSuccess = true;
		}
		else {
			$getSuccess = false;
		}
		$databaseClose = database::dbClose();
		
		return $getSuccess;
		return $getCustomerID;
		return $getCustomerNameFirst;
		return $getCustomerNameLast;
		return $getCustomerEmail;
		return $getCustomerCreated;
		return $getCustomerBillingID;
		return $getCustomerBillingNameFirst;
		return $getCustomerBillingNameLast;
		return $getCustomerBillingAddress;
		return $getCustomerBillingAddress2;
		return $getCustomerBillingCity;
		return $getCustomerBillingState;
		return $getCustomerBillingZip;
		return $getCustomerBillingCountry;
		return $getCustomerBillingPhone;
		return $getCustomerShippingArray;
	}
	
	// Update customer
	function updateCustomer($customers_id,$name_first,$name_middle,$name_last,$email) {
		global $updateSuccess;
		global $updateResult;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check to make sure email isn't in use by another customer
		$dbResult = mysql_query('SELECT customers_id FROM customers WHERE customers_email = "'.strtolower($email).'" AND customers_id <> '.$customers_id,$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			// Update customer record
			if (!empty($name_first) && !empty($name_last) && !empty($email)) {
				// Get existing UID and email address
				$dbResult = mysql_query('SELECT customers_uid, customers_email FROM customers WHERE customers_id = '.$customers_id,$databaseConnect);
				while ($dbRow = mysql_fetch_array($dbResult)) {
					$uid = $dbRow['customers_uid'];
					$current_email = $dbRow['customers_email'];
				}
				$dbResult = mysql_query('UPDATE customers SET customers_name_first = "'.addslashes($name_first).'", customers_name_middle = "'.addslashes($name_middle).'", customers_name_last = "'.addslashes($name_last).'", customers_email = "'.strtolower($email).'" WHERE customers_id = '.$customers_id,$databaseConnect);
				// Update membership sites usernames
				if ($current_email != $email) {
					$dbResult = mysql_query('SELECT sites_database FROM sites WHERE sites_type = 1',$databaseConnect);
					while ($dbRow = mysql_fetch_array($dbResult)) {
						$sites_database = $dbRow['sites_database'];
						$dbResult2 = mysql_query('UPDATE '.$sites_database.'.members SET members_username = "'.strtolower($email).'" WHERE members_uid = "'.$uid.'"',$databaseConnect);
					}
				}
				$updateSuccess = true;
				$updateResult = 'Customer information updated';			
			}
			else {
				$updateSuccess = false;
				$updateResult = 'Customer information incomplete';
			}
		}
		else {
			$updateSuccess = false;
			$updateResult = 'Email address already in use';
		}
		$databaseClose = database::dbClose();
		
		return $updateSuccess;
		return $updateResult;
	}
	
	// Update customer address
	function updateCustomerAddress($customers_id,$address_type,$address_id,$name_first,$name_last,$address,$address2,$city,$state,$zip,$country,$phone) {
		global $updateSuccess;
		global $updateResult;
		
		$addressTypeSwitch = ($address_type == 'billing') ? '1' : '2';
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Update customer record
		if (!empty($name_first) && !empty($name_last) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($country)) {
			if ($address_type == 'billing' || (!empty($address_id) && $address_type == 'shipping')) {
					// Update customer address
					$dbQuery = 'UPDATE customers_address SET 
					customers_address_name_first = "'.addslashes($name_first).'", 
					customers_address_name_last = "'.addslashes($name_last).'",
					customers_address_address_1 = "'.addslashes($address).'", 
					customers_address_address_2 = "'.addslashes($address2).'", 
					customers_address_city = "'.addslashes($city).'", 
					customers_address_state = "'.addslashes($state).'", 
					customers_address_zip = "'.addslashes($zip).'", 
					customers_address_country = "'.addslashes($country).'", 
					customers_address_phone= "'.addslashes($phone).'" WHERE customers_address_customers_id = '.$customers_id;
					$dbQuery .= ($address_type == 'shipping') ? ' AND customers_address_id = '.$address_id : '';
					$dbQuery .= ' AND customers_address_type = '.$addressTypeSwitch;
			}
			else if (empty($address_id) && $address_type == 'shipping') {
					// Insert customer shipping address
					$dbQuery = 'INSERT INTO customers_address (customers_address_id,customers_address_customers_id,customers_address_type,customers_address_name_first,customers_address_name_last,customers_address_address_1,customers_address_address_2,customers_address_city,customers_address_state,customers_address_zip,customers_address_country,customers_address_phone) VALUES (customers_address_id,'.$customers_id.',2,"'.addslashes($name_first).'","'.addslashes($name_last).'","'.addslashes($address).'","'.addslashes($address2).'","'.addslashes($city).'","'.addslashes($state).'","'.addslashes($zip).'","'.addslashes($country).'","'.addslashes($phone).'")';
			}
			// Submit query
			$dbResult = mysql_query($dbQuery,$databaseConnect);
			$updateSuccess = true;
			$updateResult = 'Customer address updated';
		}
		else {
			$updateSuccess = false;
			$updateResult = 'Customer information incomplete';
		}
		$databaseClose = database::dbClose();
		
		return $updateSuccess;
		return $updateResult;
	}
	
	// Update customer
	function deleteCustomer($customers_id) {
		global $deleteSuccess;
		global $deleteResult;
		
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		// Check to make sure customer exists
		$dbResult = mysql_query('SELECT customers_uid FROM customers WHERE customers_id = '.$customers_id,$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount != 0) {
			// Collect UID for membership sites
			while ($dbRow = mysql_fetch_array($dbResult)) {
				$customers_uid = $dbRow['customers_uid'];
			}
			// Delete customer record
			$dbResult = mysql_query('DELETE a,b FROM customers a LEFT JOIN customers_address b ON a.customers_id = b.customers_address_customers_id WHERE a.customers_id = '.$customers_id,$databaseConnect);
			// Check that customer has been removed
			$dbResult = mysql_query('SELECT customers_id FROM customers WHERE customers_id = '.$customers_id,$databaseConnect);
			$dbCount = mysql_num_rows($dbResult);
			if ($dbCount == 0) {
				// Delete customer membership sites profiles
				$dbResult = mysql_query('SELECT sites_database FROM sites WHERE sites_type = 1',$databaseConnect);
				while ($dbRow = mysql_fetch_array($dbResult)) {
					$sites_database = $dbRow['sites_database'];
					$dbResult2 = mysql_query('SELECT members_id FROM '.$sites_database.'.members WHERE members_uid = "'.$customers_uid.'"',$databaseConnect);
					$dbCount2 = mysql_num_rows($dbResult2);
					if ($dbCount2 != 0) {
						while ($dbRow2 = mysql_fetch_array($dbResult2)) {
							$members_id = $dbRow2['members_id'];
						}
						// Get all member sites tables
						$dbResult3 = mysql_query('SELECT DISTINCT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME LIKE "%members_id" AND TABLE_SCHEMA="'.$sites_database.'"',$databaseConnect);
						$dbCount3 = mysql_num_rows($dbResult3);
						if ($dbCount3 != 0) {
							while ($dbRow3 = mysql_fetch_assoc($dbResult3)) {
								$table_name = $dbRow3['TABLE_NAME'];
								$column_name = $dbRow3['COLUMN_NAME'];
								// Delete customer from member sites tables
								$dbResult4 = mysql_query('DELETE FROM '.$sites_database.'.'.$table_name.' WHERE '.$column_name.' = '.$members_id,$databaseConnect);
							}
						}
					}
				}
				$deleteSuccess = true;
				$deleteResult = 'Customer deleted';			
			}
			else {
				$deleteSuccess = false;
				$deleteResult = 'Could not delete customer';
			}
		}
		else {
			$deleteSuccess = false;
			$deleteResult = 'Could not find customer';
		}
		$databaseClose = database::dbClose();
		
		return $deleteSuccess;
		return $deleteResult;
	}
	
}
?>