<?
##########################
#iManage class: database
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class database {

	// database connection function
	function dbConnection() {
		global $databaseHost;
		global $databaseName;
		global $databaseUser;
		global $databasePass;
		global $databaseConnect;
		global $databaseSelect;
		
		$databaseConnect = mysql_connect($databaseHost,$databaseUser,$databasePass);
		$databaseSelect = mysql_select_db($databaseName,$databaseConnect);
		
		return $databaseConnect;
		return $databaseSelect;
	}
	
	// query the database
	function dbQuery($query) {
		global $databaseConnect;
		global $databaseSelect;
		global $dbResult;
		global $dbCount;
		global $dbID;
		global $dbErr;
		
		$dbCountTotal = false;
		$this->dbConnection();
		$dbResult = mysql_query($query,$databaseConnect);
		if (strstr($query,'COUNT(')) {
			$dbRow = mysql_fetch_assoc($dbResult);
			$dbCountTotal = $dbRow['total_count'];
		}
		$dbCount = (!empty($dbCountTotal)) ? $dbCountTotal : mysql_num_rows($dbResult);
		$dbID = mysql_insert_id();
		$dbErr = mysql_errno($databaseConnect);
		$this->dbClose();
		
		return $dbCount;
		return $dbResult;
		return $dbID;
		return $dbErr;
	}

	// query the database, return custom result handler
	function dbQueryRaw($query) {
		global $databaseConnect;
		global $databaseSelect;
		
		$this->dbConnection();
		return mysql_query($query,$databaseConnect);
		$this->dbClose();
	}
	
	// database close function
	function dbClose() {
		global $databaseConnect;
		
		@mysql_close($databaseConnect);
		
	}
}
?>