<?
##########################
#iManage class: logging
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class logging {

	function logIt($id,$name,$site,$module,$description) {
		global $databaseConnect;
		global $databaseSelect;
		global $databaseName;
		global $userIP;
		
		// Switch id
		$id = (empty($id)) ? 0 : $id;
		$databaseConnect = database::dbConnection();
		$databaseSelect = database::dbConnection();
		$dbResult = mysql_query('INSERT INTO '.$databaseName.'.logs VALUES (NULL,"'.date("Y-m-d H:i:s").'",'.$id.',"'.$name.'","'.$userIP.'","'.$site.'","'.$module.'","'.$description.'")',$databaseConnect);
		$databaseClose = database::dbClose();
	
	}

}
?>