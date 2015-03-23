SITE CUSTOMIZATION INSTRUCTIONS
- Justin Ziegler, 11/14

FOLDER 'sitename'
- Rename this with your new site's name. This name will be used a few times in later steps.

FOLDER 'config/email_templates/sitename'
- Rename this with your new site's name. 

FILE 'config/email_templates/sitename/welcome.txt'
- Note the location of this file - edit contents to customize the receipt email

FILE 'config/config.sitename.php'
- Rename file - replace sitename with your new site's name
- Open file and search for: sitename
  -- Replace with your new site's name (there should be several replacements)

sitename/INDEX.PHP
- Search for: $siteConfig = 'sitename';
  -- Replace sitename with: your new site's name

- Search for: $scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/sitename'));
  -- Replace sitename with: your new site's name

sitename/ORDER.PHP
- Search for: $siteConfig = 'sitename';
  -- Replace sitename with: your new site's name

- Search for: $scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/sitename'));
  -- Replace sitename with: your new site's name
  
- Search for: $emailFile = $scriptPath.'/config/email_templates/sitename/welcome.txt';  
  -- Replace sitename with: your new site's name
  
- Search for: $arrProducts = array
  -- Replace each array with your product details. 
     Sample array: ('D6VWR72'=>array('14 Day Trial of Ultra Garcinia Slim','9--23','30','47','4.95','Free Shipping','0.00')
     Translation: ('SKU'=>array('Product Description','9--23 (these increase for each product: 9--23, 10--23, 11--23, etc.)','(not sure)','(not sure)','Price','Shipping Policy','Shipping Price')

sitename/RECEIPT.PHP
- Search for: $siteConfig = 'sitename';
  -- Replace sitename with: your new site's name

- Search for: $scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/sitename'));
  -- Replace sitename with: your new site's name


TEMPLATE CUSTOMIZATION
- Sitewide customization files:
  -- templates/sitewide/includes.php: add any <head> elements to this file
  -- templates/sitewide/header.php: sitewide header
  -- templates/sitewide/footer.php: sitewide footer
  
- Customize index.php with the following files:
  -- templates/testindex.php: use this to view a test version of index.php
  -- content.php: content preceding the form
  -- submit.php: customize the submit button
  -- subcontent.php: content after the form
  
- Customize order.php with the following files:
  -- templates/testorder.php: use this to view a test version of order.php
  -- content.php: content preceding the form
  -- submit.php: customize the submit button
  -- subcontent.php: content after the form
  -- terms.php: order terms
  
- Customize receipt.php with the following files:
  -- templates/testreceipt.php: use this to view a test version of receipt.php
  -- content.php: content preceding the form
  -- submit.php: customize the submit button
  -- subcontent.php: content after the form