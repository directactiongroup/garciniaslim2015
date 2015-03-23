<?
##########################
#iManage bootstrap
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

// Session handling
session_cache_expire(1);
session_start();

// Disable errors
error_reporting(0);
ini_set('display_errors',0);

// Script path
$scriptPath = (isset($scriptPath)) ? $scriptPath : substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/'));

// Site-specific config file
$siteConfig = (isset($siteConfig) && file_exists($scriptPath.'/config/config.'.$siteConfig.'.php')) ? $siteConfig : 'imanage';

// Global includes
require_once($scriptPath.'/config/config.php');
require_once($scriptPath.'/config/config.'.$siteConfig.'.php');
require_once($scriptPath.'/app/class.database.php');
require_once($scriptPath.'/app/class.credentials.php');
require_once($scriptPath.'/app/class.cleaner.php');
require_once($scriptPath.'/app/class.customers.php');
require_once($scriptPath.'/app/class.members.php');
require_once($scriptPath.'/app/class.security.php');
require_once($scriptPath.'/app/class.users.php');
require_once($scriptPath.'/app/class.contacts.php');
require_once($scriptPath.'/app/class.validation.php');
require_once($scriptPath.'/app/class.forms.php');
require_once($scriptPath.'/app/class.mailer.php');
require_once($scriptPath.'/app/class.logging.php');

// Global objects
$dbObj = new database;
$securityObj = new security;
$cleanObj = new cleaner;
$validObj = new validation;
$mailObj = new mailer;
$logObj = new logging;

// Grab action commands
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : false;
?>