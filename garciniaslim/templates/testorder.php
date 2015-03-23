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
	background-image: url(../images/faq_bg.jpg);
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

<script type="text/javascript" language="JavaScript">
if (document.images) {
	<?
	$i = 1;
	foreach ($arrProductsKeys as $key) {
		echo "var product".$i."_off = new Image\n\n";
		echo "product".$i."_off.src = \"images/product".$i."_off.jpg\"\n\n";
		echo "var product".$i."_on = new Image\n\n";
		echo "product".$i."_on.src = \"images/product".$i."_on.jpg\"\n\n";
		$i++;
	}
	?>
}

var active_target = false;

function roll(id,name) {
	if (active_target != id) {
		if (document.images) {
			document.images[id].src=eval(name+".src");
		}
	}
}

function toggleProduct(product_id) {
	<?
	$i = 1;
	foreach ($arrProducts as $key => $val) {
		echo "var arr".$key." = {productImage:\"product".$i."\",productName:\"".$val[0]."\",productPrice:\"".$val[4]."\",shippingName:\"".$val[5]."\",shippingPrice:\"".$val[6]."\"};\n";
		$i++;
	}
	?>
	var image_target = eval("arr"+product_id).productImage;
	active_target = image_target;
	document.getElementById('product_id').value = product_id;
	<?
	$i = 1;
	foreach ($arrProductsKeys as $key) {
		echo "roll('product".$i."','product".$i."_off');\n";
		$i++;
	}
	?>
	//roll(image_target,image_target+'_on');
	//document.getElementById(image_target).src = image_target+'_on';
	document.images[image_target].src=eval(image_target+"_on.src");
	document.getElementById('product_name').innerHTML = eval("arr"+product_id).productName;
	document.getElementById('shipping_name').innerHTML = eval("arr"+product_id).shippingName;
	document.getElementById('shipping_price').innerHTML = eval("arr"+product_id).shippingPrice;
	document.getElementById('product_total').innerHTML = (Number(eval("arr"+product_id).productPrice)+Number(eval("arr"+product_id).shippingPrice)).toFixed(2);
	
}
</script>
<link href="../main.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.form1 {font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<div class="nav"><img src="../images/home_01.jpg" width="1087" height="182" border="0" usemap="#Map" />
  <map name="Map" id="Map">
    <area shape="rect" coords="612,77,753,119" href="<? echo (isset($_SESSION['CID'])) ? 'order.php' : 'index.php#order'; ?>" />
    <area shape="rect" coords="483,77,605,119" href="faq.php" />
    <area shape="rect" coords="352,78,480,119" href="index.php" />
    <area shape="rect" coords="62,4,266,117" href="index.php" />
  </map>
</div>
<a name="order"></a>
<div class="pre-container_order">
	<div class="products"><img id="product2" src="../images/product2_off.jpg" onMouseOver="roll('product2','product2_on');"  onMouseOut="roll('product2','product2_off');" onClick="toggleProduct('<? echo $arrProductsKeys[1]; ?>');" class="image_products" /><br /><img id="product3" src="../images/product3_off.jpg" onMouseOver="roll('product3','product3_on');"  onMouseOut="roll('product3','product3_off');" onClick="toggleProduct('<? echo $arrProductsKeys[2]; ?>');" class="image_products" /><br /><img id="product4" src="../images/product4_off.jpg" onMouseOver="roll('product4','product4_on');"  onMouseOut="roll('product4','product4_off');" onClick="toggleProduct('<? echo $arrProductsKeys[3]; ?>');" class="image_products" /><br /><img id="product1" src="../images/product1_off.jpg" onMouseOver="roll('product1','product1_on');"  onMouseOut="roll('product1','product1_off');" onClick="toggleProduct('<? echo $arrProductsKeys[0]; ?>');" class="image_products" /><br />
     	<div class="trial_blurb">We take great pride in the quality of our products.  If for any reason you do not find this product is right for you we will gladly give you a full refund, no questions asked. You have nothing to lose. By placing your Trial order you will be charged $4.95 and be enrolled in our refill membership program. This program will charge $59.95 for your trial of Ultra Garcinia Slim on the 14th day and ship a full-size bottle of Ultra Garcinia Slim for $59.95 + $9.95 S/H every 30 days thereafter until you cancel. You can cancel or modify your membership anytime by calling 800-260-7760. If you are not satisfied with your trial product, you must call (800)-260-7760 by day 14 to cancel your trial, or you will be charged $59.95 + $9.95 S/H.</div>
     	<div class="layout_cell" style="margin-top: 30px; margin-left: 30px;"><img src="../images/usps_logo.png" class="image_noborders" /></div>
          <div class="layout_cell" style="margin-top: 30px; margin-left: 60px;">
          	<div class="layout_cell" style="width: 140px; text-align: right"><font class="text_summary"><strong>Order Summary:</strong></font></div>
               <div class="layout_row"><img src="../images/summary_divider.png" class="image_noborders" /></div>
               <div class="layout_cell" style="width: 140px; text-align: right"><font class="text_summary">Package:</font></div>
               <div class="layout_cell" style="width: 190px; text-align: right"><font class="text_summary"><span id="product_name"></span></font></div>
               <div class="layout_row"></div>
               <div class="layout_cell" style="width: 140px; text-align: right"><font class="text_summary">Shipping Type:</font></div>
               <div class="layout_cell" style="width: 190px; text-align: right"><font class="text_summary"><span id="shipping_name"></span></font></div>
               <div class="layout_row"></div>
               <div class="layout_cell" style="width: 140px; text-align: right"><font class="text_summary">Shipping Price:</font></div>
               <div class="layout_cell" style="width: 190px; text-align: right"><font class="text_summary">$<span id="shipping_price">0.00</span></font></div>
               <div class="layout_row"><img src="../images/summary_divider.png" class="image_noborders" /></div>
               <div class="layout_cell" style="width: 140px; text-align: right"><font class="text_summary"><strong>Your Total:</strong></font></div>
               <div class="layout_cell" style="width: 190px; text-align: right"><font class="text_summary">$<span id="product_total">0.00</span></font></div>
          </div>
          <div class="layout_row"></div>
     </div>
  <div class="order">
  <form action="order.php#order" method="post" >
  <input type="hidden" name="order" value="y" />
   <input type="hidden" name="order_id" value="<? echo $_SESSION['order_sess']; ?>" />
   <input type="hidden" name="shipping_id" value="<? echo $shipping_id; ?>" />
   <input type="hidden" id="product_id" name="product_id" value="<? echo $product_id; ?>" />
    <table width="284" border="0" align="center" cellpadding="1" cellspacing="2">
    <? echo ($frmMsg != '') ? '<tr><td colspan="2"><font class="form_error">'.$frmMsg.'</font></td></tr>' : ''; ?>
      <tr>
        <td width="268"><span class="form_copy">First Name</span>:</br>
          <input name="name_first" type="text" class="field" id="name_first" value="<? echo $name_first; ?>" /></td>
      </tr>
      <tr>
        <td><span class="form_copy">Last Name</span>:</br>
          <input name="name_last" type="text" class="field" id="name_last" value="<? echo $name_last; ?>"  /></td>
      </tr>
      <tr>
        <td><span class="form_copy">Address</span>:</br>
          <input name="address" type="text" class="field" id="address" value="<? echo $address; ?>"  /></td>
      </tr>
      <tr>
        <td><span class="form_copy">City</span>:</br>
          <input name="city" type="text" class="field" id="city" value="<? echo $city; ?>"  /></td>
      </tr>
      <tr>
        <td><span class="form_copy">State</span>:</br>
          <span class="formRow">
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
        </span></td>
      </tr>
      <tr>
        <td><span class="form_copy">Zip</span>:</br>
          <input name="zip" type="text" class="field" id="zip" value="<? echo $zip; ?>" /></td>
      </tr>
      <tr>
        <td><span class="form_copy">Phone</span>:</br>
          <input name="phone" type="text" class="field" id="phone" value="<? echo $phone; ?>" /></td>
      </tr>
      <tr>
        <td><img src="../images/cc_info.png"/></td>
      </tr>
      <tr>
        <td><span class="form_copy">Card Type</span>:</br>
          <span class="form1">
          <select name="cc_type" class="field" id="cc_type">
          <option value="">Select Card Type</option>
          <option value="VISA"<? if($cc_type == 'VISA'){echo' selected';} ?>>Visa</option>
          <option value="MC"<? if($cc_type == 'MC'){echo' selected';} ?>>MasterCard</option>
          <option value="DC"<? if($cc_type == 'DC'){echo' selected';} ?>>Discover</option>
          <option value="AMEX"<? if($cc_type == 'AMEX'){echo' selected';} ?>>American Express</option>
          </select>
        </span></td>
      </tr>
      <tr>
        <td><span class="form_copy">Card Number</span>:</br>
          <input name="cc_num" type="text" class="field" id="cc_num" value="<? echo $cc_num; ?>" /></td>
      </tr>
      <tr>
        <td><span class="form_copy">Card Expiration</span>:</br>
          <select name="exp_month" id="exp_month" class="field1">
          <option value="">Month</option>
          <?
		for ($i=1;$i<=12;$i++) {
			$y = (strlen($i)<2) ? '0'.$i : $i;
			echo '<option value="'.$y.'"';
			if ($y == $exp_month) {
				echo ' selected';
			}
			echo '>'.$arrMonths[$y-1].'</option>';
		}
		?>
          </select>
          <select name="exp_year" id="exp_year" class="field1">
          <option value="">Year</option>
          <?
		for ($i=(date('Y'));$i<=(date('Y')+8);$i++) {
			echo '<option value="'.$i.'"';
			if ($i == $exp_year) {
				echo ' selected';
			}
			echo '>'.$i.'</option>';
		}
		?>
        </select></td>
      </tr>
      <tr>
        <td><span class="form_copy">Card CVV</span>:</br>
          <input name="cc_code" type="text" class="field2" id="cc_code" value="<? echo $cc_code; ?>" /></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="agree_terms" value="y"<? if ($agree_terms == 'y') {echo ' checked';} ?> /> <span class="form_copy">I agree to the website <a href="javascript:popUp('tc.html')">Terms & Conditions</a></span></td>
      </tr>
      <br />
      <tr>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td><input type="image" src="../images/buy_now.png"  width="210" height="50" /></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <input type="hidden" name="baffle" value="y" />
    </form>
  </div>
</div>
<div class="layout_row"></div>
<? echo ($product_id != '') ? '<script language="javascript">toggleProduct(\''.$product_id.'\');</script>' : ''; ?>
<div class="footer-order">
    <div align="center"> <a id="PrivacyPolicy" class="footerHyperlynks" href="javascript:popUp('pp.html')">Privacy Policy</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="TermsAndConditions" class="footerHyperlynks" href="javascript:popUp('tc.html')">Terms &amp; Conditions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </a><a id="label" class="footerHyperlynks" href="javascript:label('label.html')">Label</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a id="ContactUs" class="footerHyperlynks" href="javascript:popUp('contact.html')">Contact Us</a> 
      <div class="footer-copy" style="margin-top: 20px; margin-bottom: 40px;">This product is not for use by or sale to persons under the age of 18. This product should be used only as directed on the label. It should not be used if you are pregnant or nursing. Consult with a physician before use if you have a serious medical condition or use prescription medications. A Doctor's advice should be sought before using this and any supplemental dietary product. All trademarks and copyrights are the property of their respective owners and are not affiliated with nor do they endorse Ultra Garcinia Slim. These statements have not been evaluated by the FDA. This product is not intended to diagnose, treat, cure or prevent any disease. Individual results will vary. By using this site you agree to follow the Privacy Policy and all Terms &amp; Conditions printed on this site. Void Where Prohibited By Law. <br />*FREE Bottles given with 2 and 3 bottle packages only</div>
</div>
</div>
</body>
</html>