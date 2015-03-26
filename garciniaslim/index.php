<?
// Site-specific config file
$siteConfig = 'garciniaslim';

// Bootstrap
$scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/garciniaslim'));
require_once($scriptPath.'/app/bootstrap.php');

//error_reporting(-1);
//ini_set('display_errors',1);

// Create objects
$customerObj = new customers;
$triangleObj = new triangleMedia;

// Define variables
$page = (isset($_REQUEST['page'])) ? $cleanObj->cleanAlphaLower($_REQUEST['page']) : false;
$signup = (isset($_REQUEST['signup'])) ? $cleanObj->cleanAlphaLower($_REQUEST['signup']) : false;
$name_first = (isset($_REQUEST['name_first'])) ? $cleanObj->cleanName($_REQUEST['name_first']) : false;
$name_last = (isset($_REQUEST['name_last'])) ? $cleanObj->cleanName($_REQUEST['name_last']) : false;
$address = (isset($_REQUEST['address'])) ? $cleanObj->cleanName($_REQUEST['address']) : false;
$city = (isset($_REQUEST['city'])) ? $cleanObj->cleanName($_REQUEST['city']) : false;
$state = (isset($_REQUEST['state'])) ? $cleanObj->cleanAlphaUpper($_REQUEST['state']) : false;
$zip = (isset($_REQUEST['zip'])) ? $cleanObj->cleanZip($_REQUEST['zip']) : false;
$phone = (isset($_REQUEST['phone'])) ? $cleanObj->cleanInt($_REQUEST['phone']) : false;
$email = (isset($_REQUEST['email'])) ? $cleanObj->cleanEmail($_REQUEST['email']) : false;
$country = 'US';
$signup_pass = true;
$frmMsg = '';

// Check signup
if ($signup == 'y') {
	// Check for required shipping/personal fields
	if ($name_first && $name_last && $address && $city && $state && $zip && $phone && $email) {
		if ($validObj->validateEmail($email) == false) {
			$frmMsg = 'You must enter a valid email address.';
			$signup_pass = false;
		}
		if (strlen($phone) < 10  || strlen($phone) > 14) {
			$frmMsg .= 'You must enter a valid phone #.';
			$signup_pass = false;
		}
		if (strlen($zip) < 5) {
			$frmMsg .= 'You must enter a valid zip.';
			$signup_pass = false;
		}
		// Error message
		if ($signup_pass == false) {
			$frmMsg = str_replace('.','.<br />',$frmMsg);
		}
	}
	else {
		$frmMsg = 'Please complete all of the fields.';
		$signup_pass = false;
	}
	// Signup continue
	if ($signup_pass == true) {
		// Check to see if customer is on record
		$customerObj->checkCustomerExists($email,$name_first,$name_last,$address,$city,$state,$zip,$country);
		if ($checkSuccess == true) {
			// Assign cid to returning customer
			$createSuccess = true;
			$createResult = $checkResult;
		}
		else {
			if ($checkEmail == false) {
				$customerObj->createCustomer($name_first,'',$name_last,$email,$address,'',$city,$state,$zip,$country,$phone,$name_first,$name_last,$address,'',$city,$state,$zip,$country,$phone);
			}
		}
		// Evaluate customer creation
		if ($createSuccess == true) {
			// Create customer uid session
			$_SESSION['CID'] = $createResult;
			header('location: order.php');
		}
		else {
			$frmMsg = 'Please select a different email address.';
			$signup_pass = false;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><? echo $adminSiteName; ?></title>
			<?
			// FAQ page
			if ($page == 'faq') {
			?>
			<?
				}
				// Home page
				else {
					// Check cid session
					if (isset($_SESSION['CID'])) {
						$customerObj->getCustomer($_SESSION['CID']);
						if ($getSuccess == true) {
							$customer_id = $getCustomerID;
							// Pre-populate empty address fields
							if ($signup == false) {
								$name_first = $getCustomerBillingNameFirst;
								$name_last = $getCustomerBillingNameLast;
								$address = $getCustomerBillingAddress;
								$city = $getCustomerBillingCity;
								$state = $getCustomerBillingState;
								$zip = $getCustomerBillingZip;
								$phone = $getCustomerBillingPhone;
								$email = $getCustomerEmail;
							}
						}
						else {
							unset($_SESSION['CID']);
						}
					}
				?>
		<script type="text/javascript" src="js/popups.js"></script>
		<?php
			include 'templates/sitewide/includes.php';
		?>
    	<link href="css/style.css" rel="stylesheet">
  </head>
  <body>
			<?php include 'templates/sitewide/header.php'; ?>
			<?php include 'templates/index/content.php'; ?>
			<div id="orderForm" class="col-xs-12 col-sm-12 col-md-11">
				<a name="order-now" id="order-now"></a>
				<form action="index.php#order" method="post" >
					<input type="hidden" name="signup" value="y" />
						<? echo ($frmMsg != '') ? '<div class="error">'.$frmMsg.'</div>' : ''; ?>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<label>First Name:</label>
							<input name="name_first" type="text" class="field" id="name_first" value="<? echo $name_first; ?>" />
							<div class="clearfix"></div>
							<label>Address:</label>
							<input name="address" type="text" class="field" id="address" value="<? echo $address; ?>" />	
							<div class="clearfix"></div>
							<label>State:</label>         
							<select name="state" id="state" class="field">
								<option value="">- Select -</option>
								<?
								foreach ($regionArray as $key => $val) {
									echo '<option value="'.$key.'"';
									if ($key == $state) {
										echo ' selected';
									}
									echo '>'.ucwords(strtolower($val)).'</option>';
								}
								?>        
							</select>
							<div class="clearfix"></div>
							<label>Phone:</label>
							<input name="phone" type="text" class="field" id="phone" value="<? echo $phone; ?>" />
							<div class="clearfix"></div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<label>Last Name:</label>
							<input name="name_last" type="text" class="field" id="name_last" value="<? echo $name_last; ?>" />
							<div class="clearfix"></div>
							<label>City:</label>
							<input name="city" type="text" class="field" id="city" value="<? echo $city; ?>" />
							<div class="clearfix"></div>
							<label>Zip:</label>
							<input name="zip" type="text" class="field" id="zip" value="<? echo $zip; ?>" />
							<div class="clearfix"></div>
							<label>Email:</label>
							<input name="email" type="text" class="field" id="email" value="<? echo $email; ?>" />
							<div class="clearfix"></div>
						</div>
						<?php
								include 'templates/index/submit.php';
						?>
						<input type="hidden" name="baffle" value="y" />
					</form>
			</div>
			<?php include 'templates/index/subcontent.php';	?>
		</div>
		<?php include 'templates/sitewide/footer.php'; ?>
		<? } ?>
  </body>
</html>
