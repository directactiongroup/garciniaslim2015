<?
##########################
#iManage class: security
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class security {
	
	// Check the referrer
	function referrerCheck($kill) {
		global $refDomain;
		
		$refDomain = str_replace(array('http://','https://'),'',$_SERVER["HTTP_REFERER"]);
		$refDomain = substr($refDomain,0,strpos($refDomain,'/'));
		if ($kill == true) {
			if (!empty($refDomain) && $refDomain != $_SERVER['HTTP_HOST']) {
				die();
			}
		}
		
		return $refDomain;
	}
	
	// Check security/levels and redirect
	function securityCheck($clearance) {
		global $maxClearance;
		global $sessMax;
		
		$this->referrerCheck(1);
		$sessionExp = false;
		$timeoutMax = (isset($sessMax) && $sessMax != '') ? $sessMax*60 : 60*60;
		if (time() - $_SESSION['Created'] > $timeoutMax) {
			unset($_SESSION['LoggedIn']);
			unset($_SESSION['User']);
			unset($_SESSION['UserID']);
			unset($_SESSION['UserAgent']);
			unset($_SESSION['Sites']);
			unset($_SESSION['Security']);
			unset($_SESSION['Affiliates']);
			unset($_SESSION['Created']);
			unset($_SESSION['EditSite']);
    			session_destroy();
			$sessionExp = true;
		}
		if (isset($_SESSION['LoggedIn']) && isset($_SESSION['User']) && isset($_SESSION['UserID']) && isset($_SESSION['Security'])) {
			if ($_SESSION['Security'][$_SESSION['Site']] > $maxClearance) {
				unset($_SESSION['LoggedIn']);
				unset($_SESSION['User']);
				unset($_SESSION['UserID']);
				unset($_SESSION['UserAgent']);
				unset($_SESSION['Sites']);
				unset($_SESSION['Security']);
				unset($_SESSION['Affiliates']);
				unset($_SESSION['Created']);
				unset($_SESSION['EditSite']);
				header('location: ?manage=login');
			}
			if ($_SESSION['Security'][$_SESSION['Site']] > $clearance) {
				header('location: ?manage=home');
			}
		}
		else {    
			// Get URI elements
		    $url = parse_url($_SERVER['REQUEST_URI']);
		    if ($url['query'] != '') {
				  session_start();
				  $_SESSION['RequestURL'] = $url['query'];        
		    }
			if ($sessionExp == true) {
				header('location: ?manage=login&expire=y');
			}
			else {
				header('location: ?manage=login');
			}
			die();
		}
	
	}
	
	// Secure content files from being viewed outside of trunk
	function trunkCheck($siteTrunk) {
		global $adminURL;
		
		if (strstr($_SERVER['REQUEST_URI'],'.php')) {
			header('location: '.$adminURL);
		}
	
	}
	
	// Secure ajax files from being viewed outside of trunk
	function ajaxCheck($ajaxFile) {
		global $adminURL;
		
		$refDomain = $this->referrerCheck(0);
		if (empty($_SERVER['HTTP_REFERER']) || $refDomain != $_SERVER['HTTP_HOST']) {
			header('location: '.$adminURL);
			die();	
		}
		
	}
	
	// Check security/levels and redirect
	function limitUser($clearanceLevel,$manage,$id,$attemptAct,$allowAct,$allowStr) {
		global $databaseConnect;
		global $databaseSelect;
		global $clearID;
		global $clearAct;
		global $adminSiteVar;
	
		$clearID = $id;
		$clearAct = $attemptAct;
		if ($_SESSION['Security'][$_SESSION['Site']] > $clearanceLevel) {
			$clearID = $_SESSION['UserID'];
			$allowArray = explode(',',$allowStr);
			if (!in_array($attemptAct,$allowArray)) {
				$clearAct = $allowAct;
			}
		}
		// Punk buster
		if ($attemptAct == 'update' && $manage == 'users') {
			$dbResult = mysql_query('SELECT users_security FROM users WHERE users_id = '.$id,$databaseConnect);
			$dbCount = mysql_num_rows($dbResult);
			$dbRow = mysql_fetch_array($dbResult);
			if ($dbRow['users_security'] < $_SESSION['Security'][$adminSiteVar]) {
				echo '<script language="javascript">window.location = "?manage='.$manage.'"</script>';
				die();
			}
		}
		else if ($attemptAct == 'update' && $manage == 'contacts') {
			$dbResult = mysql_query('SELECT a.users_security FROM users a LEFT JOIN contacts b ON a.users_id = b.contacts_users_id WHERE b.contacts_id = '.$id,$databaseConnect);
			$dbCount = mysql_num_rows($dbResult);
			$dbRow = mysql_fetch_array($dbResult);
			if ($dbCount == 1 && $dbRow['users_security'] < $_SESSION['Security'][$adminSiteVar]) {
				echo '<script language="javascript">window.location = "?manage='.$manage.'"</script>';
				die();
			}		
		}
		
		return $clearID;
		return $clearAct;
	}
	
	// Basic user log in check for membership sites
	function loginMemberCheck($restrict) {
		global $loggedIn;
		global $adminSiteURL;
		global $adminSiteVar;
		
		$loggedIn = false;
		if (isset($_SESSION[''.$adminSiteVar.'_LoggedIn']) && $_SESSION[''.$adminSiteVar.'_LoggedIn'] == 1) { 
			$loggedIn = true;
		}
		// Optional page restriction
		if ($restrict == '1' && $loggedIn == false) {
			die();
		}
		if ($restrict == '2' && $loggedIn == false) {
			header('location: '.$adminSiteURL);
			die();
		}
		
		return $loggedIn;
		
	}
	
	// Create hash key
	function createHash() {
		global $databaseConnect;
		global $databaseSelect;
		global $hashStr;
		
		$this->deleteHash();
		$hashStr = md5(session_id());
		$dbResult = mysql_query('INSERT INTO security_hash VALUES ("'.$hashStr.'")',$databaseConnect);
		
		return $hashStr;
		
	}
	
	// Check hash key
	function checkHash($hashStr) {
		global $databaseConnect;
		global $databaseSelect;
		
		$dbResult = mysql_query('SELECT * FROM security_hash WHERE security_hash_code = "'.$hashStr.'"',$databaseConnect);
		$dbCount = mysql_num_rows($dbResult);
		if ($dbCount == 0) {
			header('location: ?manage=login');
			die();
		}
		
	}
	
	// Delete hash key
	function deleteHash() {
		global $databaseConnect;
		global $databaseSelect;
		
		$dbResult = mysql_query('DELETE FROM security_hash',$databaseConnect);
		
	}

}
?>