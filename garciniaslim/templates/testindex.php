<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>test</title>
		<script type="text/javascript" src="../js/popups.js"></script>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/style.css" rel="stylesheet">
	</head>
  	<body>
			<?php include 'sitewide/header.php';?>
			<?php include 'index/content.php';?>
			<div id="orderForm" class="col-xs-12 col-sm-12 col-md-11">
				<a name="order-now" id="order-now"></a>
				<form action="../index.php#order" method="post" >
					<input type="hidden" name="signup" value="y" />
					<div class="error">Please complete all of the fields.</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<label>First Name</label>
						<input name="name_first" type="text" class="field" id="name_first" value="<? echo $name_first; ?>" tabindex=1 />
						<div class="clearfix"></div>
						<label>Address</label>
						<input name="address" type="text" class="field" id="address" value="<? echo $address; ?>" tabindex=3 />	
						<div class="clearfix"></div>
						<label>State</label>         
						<select name="state" id="state" class="field" tabindex=5 >
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
						<label>Phone</label>
						<input name="phone" type="text" class="field" id="phone" value="<? echo $phone; ?>" tabindex=7 />
						<div class="clearfix"></div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<label>Last Name</label>
						<input name="name_last" type="text" class="field" id="name_last" value="<? echo $name_last; ?>" tabindex=2 />
						<div class="clearfix"></div>
						<label>City</label>
						<input name="city" type="text" class="field" id="city" value="<? echo $city; ?>" tabindex=4 />
						<div class="clearfix"></div>
						<label>Zip</label>
						<input name="zip" type="text" class="field" id="zip" value="<? echo $zip; ?>" tabindex=6 />
						<div class="clearfix"></div>
						<label>Email</label>
						<input name="email" type="text" class="field" id="email" value="<? echo $email; ?>" tabindex=8 />
						<div class="clearfix"></div>
					</div>
					
					
					<?php include 'index/submit.php';?>
					<input type="hidden" name="baffle" value="y" />
				</form>
			</div>
		<?php include 'index/subcontent.php';?>
	</div>
	<?php include 'sitewide/footer.php';?>
  </body>
</html>
