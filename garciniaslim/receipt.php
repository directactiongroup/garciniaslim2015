<?
// Site-specific config file
$siteConfig = 'garciniaslim';

// Bootstrap
$scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/garciniaslim'));
require_once($scriptPath.'/app/bootstrap.php');

//error_reporting(-1);
//ini_set('display_errors',1);

// Define variables
$page = (isset($_REQUEST['page'])) ? $cleanObj->cleanAlphaLower($_REQUEST['page']) : false;

// Write no-cache headers
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Check order session
if (!isset($_SESSION['order_details']) || empty($_SESSION['order_details'])) {
	header('location: index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><? echo $adminSiteName; ?></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #fff;
	background-image: url(images/faq_bg.jpg);
	background-repeat: repeat-x;
}
</style>
<script type="text/javascript" language="JavaScript">
   function popUp(URL) {
	  day = new Date();
	  id = day.getTime();
	  eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,status=2,menubar=0,resizable=0,width=650,height=800,left = 50,top = 50');");
   }
</script>

<script type="text/javascript" language="JavaScript">
   function label(URL) {
	  day = new Date();
	  id = day.getTime();
	  eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,status=2,menubar=0,resizable=0,width=1000,height=375,left = 50,top = 50');");
   }
</script>
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="nav"><img src="images/home_01.jpg" width="1087" height="182" border="0" usemap="#Map" />
  <map name="Map" id="Map">
    <area shape="rect" coords="612,77,753,119" href="index.php#order" />
    <area shape="rect" coords="483,77,605,119" href="faq.php" />
    <area shape="rect" coords="352,78,480,119" href="index.php" />
    <area shape="rect" coords="62,4,266,117" href="index.php" />
  </map>
</div>
<a name="order"></a>
<div class="pre-container_receipt">
	<div class="order_summary">
     	<div class="layout_cell" style="width: 300px;"><strong>Billing Address:</strong><br /><? echo $_SESSION['order_details']['billing_address']; ?></div>
          <div class="layout_cell" style="width: 300px; margin-left: 50px;"><strong>Shipping Address:</strong><br /><? echo $_SESSION['order_details']['shipping_address']; ?></div>
          <div class="layout_row"><br /><br /><br /><br /></div>
          <div class="layout_cell" style="width: 600px;"><strong>Products Ordered:</strong><br /><? echo $_SESSION['order_details']['product_name']; ?><br />
		<div class="layout_cell" style="width: 120px;">Price:</div>
		<div class="layout_cell" style="width: 300px;">$<? echo $_SESSION['order_details']['product_price']; ?></div>
		<div class="layout_row"></div>
          <div class="layout_cell" style="width: 120px;">Shipping:</div>
		<div class="layout_cell" style="width: 300px;"><? echo $_SESSION['order_details']['shipping_name'].' $'.$_SESSION['order_details']['shipping_price']; ?></div>
		<div class="layout_row"></div>
          <div class="layout_cell" style="width: 120px;">Total:</div>
		<div class="layout_cell" style="width: 300px;">$<? echo $_SESSION['order_details']['order_total']; ?></div>
          <div class="layout_row"></div>
     </div>
     <div class="layout_row"></div>
     </div>
</div>
<div class="layout_row"></div>
<div class="footer-order">
    <div align="center"> <a id="PrivacyPolicy" class="footerHyperlynks" href="javascript:popUp('pp.html')">Privacy Policy</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="TermsAndConditions" class="footerHyperlynks" href="javascript:popUp('tc.html')">Terms &amp; Conditions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </a><a id="label" class="footerHyperlynks" href="javascript:label('label.html')">Label</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a id="ContactUs" class="footerHyperlynks" href="javascript:popUp('contact.html')">Contact Us</a> 
      <div class="footer-copy" style="margin-top: 20px; margin-bottom: 40px;">This product is not for use by or sale to persons under the age of 18. This product should be used only as directed on the label. It should not be used if you are pregnant or nursing. Consult with a physician before use if you have a serious medical condition or use prescription medications. A Doctor's advice should be sought before using this and any supplemental dietary product. All trademarks and copyrights are the property of their respective owners and are not affiliated with nor do they endorse Ultra Garcinia Slim. These statements have not been evaluated by the FDA. This product is not intended to diagnose, treat, cure or prevent any disease. Individual results will vary. By using this site you agree to follow the Privacy Policy and all Terms &amp; Conditions printed on this site. Void Where Prohibited By Law. <br />*FREE Bottles given with 2 and 3 bottle packages only</div>
</div>
</div>
</body>
</html>
