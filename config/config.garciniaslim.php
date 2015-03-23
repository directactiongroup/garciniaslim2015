<?
##########################
#iManage config: garciniaslim
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

// Site paths 
$adminSiteName = 'Ultra Garcinia Slim';                         
$adminSitePath = '../garciniaslim';                         
$adminSiteURL = 'http://secretlabstudio.local/Direct_Action_Group/garciniaslim';      
$adminSiteVar = 'garciniaslim';     

// State/Region codes// Set default timezone
date_default_timezone_set('America/Los_Angeles');

// Upload settings
$allowImg = array('gif','jpg','png');
$allowAV = array('mp3','mp4','mov','mpg','avi','flv');
$allowDoc = array('pdf','doc','docx','xls','xlsx','ppt','pptx');
$uploadPostLimit = ini_get('max_file_uploads');  // Files
$uploadPostSizeLimit = ini_get('post_max_size');  // Megabytes
$uploadSizeLimit = ini_get('upload_max_filesize');  // Megabytes      

// Set skins directory
$skinsDir = 'default';                                             

// Database credentials
$siteDBName = 'dagroup';

// Mail settings
$mailEmail = 'support@garciniaslim.com';  
$mailConfEmail = 'inquiries@garciniaslim.com';                  
$mailSender = 'Ultra Garcinia Slim';
$mailContact = 'inquiries@garciniaslim.com';                  

// Date/Time settings
$dateBegin = 2013;    

// Admin sections
$adminSections = array('news'=>'News','blog'=>'Blog','photos'=>'Images','videos'=>'Audio & Video','portfolio'=>'Portfolio','documents'=>'Documents','productions'=>'Productions','content'=>'Site Content');

// Site-specific config

// Additional config settings & modules
require_once($scriptPath.'/app/class.triangleMedia.php');
require_once($scriptPath.'/config/credentials/cred.triangleMedia.php');
require_once($scriptPath.'/app/class.awebber.php');
require_once($scriptPath.'/config/region/configUS.php');

// End site-specific config
?>