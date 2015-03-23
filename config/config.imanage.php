<?
##########################
#iManage config: iManage
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

// Set site vars
$adminName = 'Direct Action Group CMS';                                                         
$adminURL = 'https://admin.directactiongroup.com';
$adminCity = 'Santa Barbara';                                                          
$adminState = 'CA';                                                          
$adminZip = '32601';
$adminCountry = 'US';
$adminTimeZone = 'America/Los_Angeles';                                                       

// State/Region codes
if (file_exists($scriptPath.'/config/region/config'.$adminCountry.'.php')) {
	include($scriptPath.'/config/region/config'.$adminCountry.'.php');
}

// Set default timezone
date_default_timezone_set($adminTimeZone);

// Clearance levels
$maxClearance = 5;
$clearanceLevels = array('Super User'=>'Configures and administers every level of the admin.','Administrator'=>'Administers other users. Full content editing and publishing.','Publisher'=>'Full content editing and publishing.','Editor'=>'Content editing only.','Visitor'=>'Content viewing only.');

// Admin core
$adminCore = array('home','login','configure','uploads','users','contacts','logs','sites','products');

// Upload settings
$allowImg = array('gif','jpg','png');
$allowAV = array('mp3','mp4','mov','mpg','avi','flv');
$allowDoc = array('pdf','doc','docx','xls','xlsx','ppt','pptx');
$uploadPostLimit = ini_get('max_file_uploads');  // Files
$uploadPostSizeLimit = ini_get('post_max_size');  // Megabytes
$uploadSizeLimit = ini_get('upload_max_filesize');  // Megabytes

// Database credentials
$siteDBName = 'dagroup';

// Site paths
$adminSitePath = '../';                              
$adminSiteURL = 'http://www.directactiongroup.com';        
$adminSiteVar = 'directaction';         

// Set skins directory
$skinsDir = 'default';   

// Mail settings
$mailEmail = 'info@directactiongroup.com';  
$mailConfEmail = 'info@directactiongroup.com';                   
$mailSender = $adminName;
$mailContact = 'info@directactiongroup.com';                         

// Date/Time settings
$dateBegin = 2001;         

// Admin sections
$adminSections = array('news'=>'News','blog'=>'Blog','photos'=>'Images','videos'=>'Audio & Video','documents'=>'Documents','portfolio'=>'Portfolio','content'=>'Site Content');

// Cron password
$cronStr = 'DA18894jNmrt6345'; 

// Grab action commands
$manage = (isset($_REQUEST['manage'])) ? $_REQUEST['manage'] : 'login';   

// Site-specific config

// Session timer settings
$sessTimer = (isset($sessTimer)) ? $sessTimer : true;
$sessTimeout = 30;
$sessMax = 4800;
if ($sessTimer == true) {
	$_SESSION['LastActive'] = time();
}

// Disable cache
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
header("Last-Modified: ".date('D, d M Y H:i:s',time()).' '.date('e'));

// Logged in
$loggedIn = (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == 1) ? true : false;

// User IP
$userIP = ($_SERVER['REMOTE_ADDR'] == '::1') ? 'Localhost' : $_SERVER['REMOTE_ADDR'];

// Set admin site
if (isset($_REQUEST['set_site']) && array_key_exists($_REQUEST['set_site'],$_SESSION['Sites'])) {
	$_SESSION['EditSite'] = $_REQUEST['set_site'];
}
if (!isset($_SESSION['EditSite']) && count($_SESSION['Sites']) == 1) {
	$_SESSION['EditSite']	= key($_SESSION['Sites']);
}

// Include additional site modules and config if different from core
/*
$siteVar = (isset($_SESSION['EditSite']) && $_SESSION['EditSite'] != '') ? $_SESSION['EditSite'] : false;
$modulePath = 'modules/'.$siteVar.'/';
if ($siteVar != false && $siteVar != $siteConfig && file_exists($scriptPath.'/config/config.'.$siteVar.'.php')) {
	require_once($scriptPath.'/config/config.'.$siteVar.'.php');	
}
*/

// FTP credentials
$ftpServer = '98.173.27.28';
$ftpUser = 'dagftp';
$ftpPass = 'DAGftp23!'; 

// Default campaign
$adminCampaign = '805';

// End site-specific config
?>