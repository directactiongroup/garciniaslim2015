<?
##########################
#iManage class: tracking
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class tracking {
	
	// Create new customer
	function trackMembers($customers_uid,$url,$referrer) {
		global $createSuccess;
		global $createResult;
		
		// Insert member records
		if (isset($customers_uid) && strlen($customers_uid)) {
			$databaseConnect = database::dbConnection();
			$databaseSelect = database::dbConnection();
			$dbResult = mysql_query('INSERT INTO tracking VALUES (tracking_id,"'.$customers_uid.'","'.date('Y-m-d H:i:s').'","'.$url.'","'.$referrer.'")',$databaseConnect);
			$databaseClose = database::dbClose();
		}
		
		return $createSuccess;
		return $createResult;
	
	}
}
?>