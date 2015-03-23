<?
##########################
#iManage class: triangleMedia
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class triangleMedia {
	
	// One-time purchase transaction
	function triangleMediaPurchase($credentials,$test,$customerNumber,$orderNumber,$arrProducts,$totalPrice,$trialPrice,$trialDays,$conversionPrice,$recurringPrice,$recurringDays,$recurringCount,$productTaxRate,$billingNameFirst,$billingNameLast,$billingAddress,$billingAddress2,$billingCity,$billingState,$billingZip,$billingCountry,$billingPhone,$emailAddress,$shippingNameFirst,$shippingNameLast,$shippingAddress,$shippingAddress2,$shippingCity,$shippingState,$shippingZip,$shippingCountry,$shippingPhone,$shippingPrice,$ccType,$ccNumber,$ccExpiration,$ccCvvCode,$customFields) {
		global $orderPlaced;
		global $orderCode;
		global $orderMsg;
		
		// Extract credentials
		$arrCredentials = explode('||',$credentials);
		$arrSplit = explode('::',$arrCredentials[0]);
		$username = $arrSplit[1];
		$arrSplit = explode('::',$arrCredentials[1]);
		$password = $arrSplit[1];
		// Get productID
		$productTypeID = false;
		$productID = false;
		$productIDs = $arrProducts[0][0];
		if ($productIDs != '') {
			$arrProductIDs = explode('--',$productIDs);
			$productTypeID = $arrProductIDs[1];
			$productID = $arrProductIDs[0];
		}
		// Get trial and plan ID
		$trialID = $customFields['trialID'];
		$planID = $customFields['planID'];
		// Build transaction
		$arrPayment = array('AMEX'=>'1','VISA'=>'2','MC'=>'3','DC'=>'4');
		$type = ($planID != '') ? 2 : 1;
		$soap_function = ($type == 2) ? 'CreateSubscription' : 'Charge';
		$trial_charge = ($trialPrice != '' && number_format($trialPrice,2) > 0.00) ? 'true' : 'false';
		$payment_type = $arrPayment[$ccType];
		$exp_date = explode('/',$ccExpiration);
		$post_url = ($test == 'y') ? 'https://directaction.trianglecrm.com/api/2.0/billing_ws.asmx' : 'https://directaction.trianglecrm.com/api/2.0/billing_ws.asmx';
		// Build xml to post
		$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
		"<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\n".
			"<soap:Body>\n".
				"<".$soap_function." xmlns=\"http://trianglecrm.com/\">\n".
					"<username>".$username."</username>\n".
					"<password>".$password."</password>\n";
					switch ($type) {
						case '1':
							$post_xml .= "<amount>".$totalPrice."</amount>\n";
							if ($productTypeID != '' && $productID != '') {
								$post_xml .=  "<productTypeID>".$productTypeID."</productTypeID>\n";
								$post_xml .= "<productID>".$productID."</productID>\n";
							}
						break;
						case '2':
							$post_xml .= "<chargeForTrial>".$trial_charge."</chargeForTrial>\n".
							"<trialPackageID>".$trialID."</trialPackageID>\n".
							"<planID>".$planID."</planID>\n";
						break;
					}
					$post_xml .= "<firstName>".$billingNameFirst."</firstName>\n".
					"<lastName>".$billingNameLast."</lastName>\n".
					"<address1>".$billingAddress."</address1>\n".
					"<address2>".$billingAddress2."</address2>\n".
					"<city>".$billingCity."</city>\n".
					"<state>".$billingState."</state>\n".
					"<zip>".$billingZip."</zip>\n".
					"<phone>".$billingPhone."</phone>\n".
					"<email>".$emailAddress."</email>\n".
					"<ip>".$_SERVER['REMOTE_ADDR']."</ip>\n".
					"<affiliate>".$customFields['affiliateID']."</affiliate>\n".
					"<subAffiliate></subAffiliate>\n".
					//"<campaignID>".$customFields['campaignID']."</campaignID>\n".
					"<internalID>".$customerNumber."</internalID>\n".
					"<paymentType>".$payment_type."</paymentType>\n".
					"<creditCard>".$ccNumber."</creditCard>\n".
					"<cvv>".$ccCvvCode."</cvv>\n".
					"<expMonth>".$exp_date[0]."</expMonth>\n".
					"<expYear>".$exp_date[1]."</expYear>\n".
				"</".$soap_function.">\n".
			"</soap:Body>\n".
		"</soap:Envelope>";
		//echo $post_xml;
		//die();
		// Initialize curl and send
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, $post_url);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_HTTPHEADER, Array('Content-type: text/xml;charset="utf-8"','Accept: text/xml','Cache-Control: no-cache','Pragma: no-cache','SOAPAction: "http://trianglecrm.com/'.$soap_function.'"','Content-length: '.strlen($post_xml)));
		curl_setopt($request, CURLOPT_HEADER, 1);
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_xml);
		curl_setopt($request, CURLOPT_POST, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
		$post_response = curl_exec($request);
		curl_close ($request);
		// Response
		//echo $post_response;
		$post_response = substr($post_response,strpos($post_response,'<'));
		$xmlParser = xml_parser_create();
		xml_parse_into_struct($xmlParser,$post_response,$xmlVals,$xmlIndex);
		xml_parser_free($xmlParser);
		$responseCode = $xmlVals[$xmlIndex["STATE"][0]]["value"];
		$responseMessage = $xmlVals[$xmlIndex["ERRORMESSAGE"][0]]["value"];
		//echo $responseCode.':'.$responseMessage;
		// Return response codes
		if ($responseCode == 'Success') {
			$orderPlaced = true;
			$orderCode = 'DA-'.date('YmdHis');
			$orderMsg = 'Order successfully processed';
		}
		else {
			$orderPlaced = false;
			// Extract decline reason
			if (strstr(strtolower($responseMessage),'issuer declined')) {
				$orderCode = 501;
				$orderMsg = $responseMessage;
			}
			else if (strstr(strtolower($responseMessage),'invalid')) {
				$orderCode = 502;
				$orderMsg = $responseMessage;
			}
			else if (strstr(strtolower($responseMessage),'insufficient funds')) {
				$orderCode = 503;
				$orderMsg = $responseMessage;
			}
			else {
				$orderCode = false;
				$orderMsg = $responseMessage;
			}
		}
						
		return $orderPlaced;
		return $orderCode;
		return $orderMsg;
	}
	
	// Add leads to Triangle dialer list
	function triangleMediaAddLeads($credentials,$test,$leadSource,$listName,$arrLeads,$callTimeOffset,$arrAddFields,$notifyRecipient) {
		global $leadAdded;
		global $leadCode;
		global $leadMsg;
		global $reportSuccess;
		global $reportResult;
		
		// Extract credentials
		$arrCredentials = explode('||',$credentials);
		$arrSplit = explode('::',$arrCredentials[0]);
		$username = $arrSplit[1];
		$arrSplit = explode('::',$arrCredentials[1]);
		$password = $arrSplit[1];
		// Responses array
		$arrResponses = array('0'=>'Success','1'=>'Invalid Campaign GUID','2'=>'Invalid Phone Number Format','3'=>'System Error');
		// Build transaction
		$post_url = ($test == 'y') ? 'http://telephony.trianglecrm.com/tridialer/dialer_lead_add.php' : 'http://telephony.trianglecrm.com/tridialer/dialer_lead_add.php';
		// Loop through leads
		$countSuccess = 0;
		$countFail = 0;
		$leadMsg = '';
		foreach ($arrLeads as $lead) {
			// Build fields array
			$leadNameFirst = $lead[0];
			$leadNameLast = $lead[1];
			$leadAddress = $lead[2];
			$leadAddress2 = $lead[3];
			$leadCity = $lead[4];
			$leadState = $lead[5];
			$leadZip = $lead[6];
			$leadCountry = $lead[7];
			$leadPhone = $lead[8];
			$leadPhone2 = $lead[9];
			$leadIP = $lead[10];
			$leadCompany = $lead[11];
			$leadRegID = $lead[12];
			// Fix the phone number for Triangle
			$phonePre = ($leadCountry == 'US' || $leadCountry == 'CA') ? '1' : '011';
			// Phone number 1
			if (substr($leadPhone,0,strlen($phonePre)) != $phonePre) {
				if ($phonePre === '1') {
					$leadPhone = $phonePre.$leadPhone;
				}
				else {
					if (substr($leadPhone,0,2) == '11') {
						$leadPhone = '0'.$leadPhone;
					}
					else {
						$leadPhone = $phonePre.$leadPhone;
					}
				}
			}
			// Phone number 2
			if (substr($leadPhone2,0,strlen($phonePre)) != $phonePre) {
				if ($phonePre === '1') {
					$leadPhone2 = $phonePre.$leadPhone2;
				}
				else {
					if (substr($leadPhone2,0,2) == '11') {
						$leadPhone2 = '0'.$leadPhone2;
					}
					else {
						$leadPhone2 = $phonePre.$leadPhone2;
					}
				}
			}
			// Build Triangle fields array
			$arrFields = array();
			$arrFields['campaign_guid'] = $listName;
			$arrFields['affiliate'] = '';
			$arrFields['sub_affiliate'] = '';
			$arrFields['reference_id'] = $leadRegID;
			$arrFields['phone'] = $leadPhone;
			$arrFields['drg_id'] = 1;
			$arrFields['trg_id'] = 1;
			// Format post string
			$strPost = '';
			foreach ($arrFields as $key => $val) {
				$strPost .= $key.'='.urlencode($val).'&';
			}
			$post_xml = rtrim($strPost,'&');
			// Top level authentication
			$request = curl_init();
			curl_setopt($request, CURLOPT_URL, $post_url);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_xml);
			curl_setopt($request, CURLOPT_POST, 1);
			$post_response = curl_exec($request);
			curl_close ($request);
			// Response
			if (strstr($post_response,'<status>')) {
				$strStart = strpos($post_response,'<status>')+strlen('<status>');
				$strEnd = strpos($post_response,'</status>');
				$postStatus = substr($post_response,$strStart,($strEnd-$strStart));
			}
			if (strstr($post_response,'<item>')) {
				$strStart = strpos($post_response,'<item>')+strlen('<item>');
				$strEnd = strpos($post_response,'</item>');
				$leadID = substr($post_response,$strStart,($strEnd-$strStart));
			}
			// Return response codes
			if ($postStatus === '0') {
				if ($leadID != '') {
					$this->triangleMediaAddLeadsResult($credentials,$leadID);
					if ($reportSuccess == true) {
						$leadCode = $leadID;
						$countSuccess++;
					}
					else {
						$leadCode = false;
						$countFail++;
						$failMsg .= "Could not add lead ".$leadNameFirst." ".$leadNameLast." to ".$listName." (".$reportResult."),";
					}
				}
				else {
					$leadCode = false;
					$countFail++;
					$failMsg .= "Could not add lead ".$leadNameFirst." ".$leadNameLast." to ".$listName." (Failed to generate lead ID),";
				}
			}
			else {
				$leadCode = false;
				$countFail++;
				$failMsg .= "Could not add lead ".$leadNameFirst." ".$leadNameLast." to ".$listName." (".$arrResponses[$postStatus]."),";
			}
		}
		// Evaluate results of entire call
		if ($countSuccess > 0) {
			$leadAdded = true;
			$leadCode = date('YmdHis');
		}
		$leadMsg = 'Records Inserted:'.$countSuccess.',';
		$leadMsg .= ($countFail > 0) ? ' Records Failed:'.$countFail.', {'.substr($failMsg,0,-1).'}' : '';
						
		return $leadAdded;
		return $leadCode;
		return $leadMsg;
	}
	
	// Add leads to Triangle dialer list
	function triangleMediaAddLeadsResult($credentials,$leadID) {
		global $reportSuccess;
		global $reportResult;
		
		// Extract credentials
		$arrCredentials = explode('||',$credentials);
		$arrSplit = explode('::',$arrCredentials[0]);
		$username = $arrSplit[1];
		$arrSplit = explode('::',$arrCredentials[1]);
		$password = $arrSplit[1];
		// Build transaction
		$post_url = 'http://telephony.trianglecrm.com/tridialer/dialer_lead_get.php';
		$post_xml = 'guid='.$leadID;
		// Initialize curl and send
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, $post_url);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_xml);
		curl_setopt($request, CURLOPT_POST, 1);
		$post_response = curl_exec($request);
		curl_close ($request);
		// Response
		if (strstr($post_response,'<status>')) {
			$strStart = strpos($post_response,'<status>')+strlen('<status>');
			$strEnd = strpos($post_response,'</status>');
			$postStatus = substr($post_response,$strStart,($strEnd-$strStart));
		}
		if (strstr($post_response,'<item>')) {
			$strStart = strpos($post_response,'<item>')+strlen('<item>');
			$strEnd = strpos($post_response,'</item>');
			$leadDetails = substr($post_response,$strStart,($strEnd-$strStart));
		}
		// Return response codes
		if ($postStatus === '0') {
			$reportSuccess = true;
			$reportResult = $leadDetails;
		}
		else {
			$reportSuccess = false;
			$reportResult = $leadDetails;
		}
				
		return $reportSuccess;
		return $reportResult;
	}
	
	// Get sales reports from Triangle
	function triangleMediaGetSalesReport($credentials,$url,$dateStart,$dateEnd,$arrVariables) {
		global $reportSuccess;
		global $reportResult;
		global $arrResult;
		
		// Extract credentials
		$arrCredentials = explode('||',$credentials);
		$arrSplit = explode('::',$arrCredentials[2]);
		$username = $arrSplit[1];
		$arrSplit = explode('::',$arrCredentials[3]);
		$password = $arrSplit[1];
		// Build transaction
		$post_url = $url;
		// Build xml to post
		$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
		"<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\n".
			"<soap:Body>\n".
				"<GetAgentSalesDetail xmlns=\"http://trianglecrm.com/\">\n".
					"<username>".$username."</username>\n".
					"<password>".$password."</password>\n".
					"<startDate>".$dateStart."</startDate>\n".
					"<endDate>".$dateEnd."</endDate>\n".
				"</GetAgentSalesDetail>\n".
			"</soap:Body>\n".
		"</soap:Envelope>";
		// Initialize curl and send
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, $post_url);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_HTTPHEADER, Array('Content-type: text/xml;charset="utf-8"','Accept: text/xml','Cache-Control: no-cache','Pragma: no-cache','SOAPAction: "http://trianglecrm.com/GetAgentSalesDetail"','Content-length: '.strlen($post_xml)));
		curl_setopt($request, CURLOPT_HEADER, 1);
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_xml);
		curl_setopt($request, CURLOPT_POST, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
		$post_response = curl_exec($request);
		curl_close ($request);
		//echo $post_response;
		// Response
		if (strstr($post_response,'<State>')) {
			$strStart = strpos($post_response,'<State>')+strlen('<State>');
			$strEnd = strpos($post_response,'</State>');
			$postStatus = substr($post_response,$strStart,($strEnd-$strStart));
		}
		// Return response codes
		if ($postStatus === 'Success') {
			$reportSuccess = true;
			// Get report array
			$str1 = '<ReturnValue>';
			$str2 = '</ReturnValue>';
			$strXML = "<salesReport>\n".substr($post_response,strpos($post_response,$str1)+strlen($str1),strrpos($post_response,$str2)-(strpos($post_response,$str1)+strlen($str1)))."\n</salesReport>";
			// Load xml string into array
			$xmlParser = xml_parser_create();
			xml_parse_into_struct($xmlParser,$strXML,$arrVals,$arrIndex);
			xml_parser_free($xmlParser);
			$arrRaw = simplexml_load_string($strXML);
			// Read results into standardized array
			//OrderID
			//OrderAgent
			//OrderCustomer (customer id, first name, last name, address, address2, city, state, zip, country, phone, email)
			//OrderDate
			//OrderTotal
			//OrderTax
			//OrderShipping
			//OrderDetails (product skus, quantity, price per unit)
			//OrderHasParent (whether it’s related to another order, then that order’s ID)
			//OrderIsUpsell (whether it’s an upsell for another order)
			//OrderIsSubscription (whether it’s a recurring subscription)
			//OrderSubscriptionBillingCycle (number of days between each billing)
			foreach ($arrRaw as $agent) {
				// Agent details
				//$agent['AgentName'];
				$OrderAgent = $agent['AgentName'];
				// Customer details
				$arrCustomer = array();
				foreach ($agent->Customers->CustomerSaleView as $customer) {
					$arrCustomer['CustomerID'] = (string)$customer->Customer->BillingID;
					$arrCustomer['BillingNameFirst'] = (string)ucwords(strtolower($customer->Customer->FirstName));
					$arrCustomer['BillingNameLast'] = (string)ucwords(strtolower($customer->Customer->LastName));
					$arrCustomer['BillingAddress'] = (string)ucwords($customer->Customer->Address1);
					$arrCustomer['BillingAddress2'] = (string)ucwords($customer->Customer->Address2);
					$arrCustomer['BillingCity'] = (string)ucwords(strtolower($customer->Customer->City));
					$arrCustomer['BillingState'] = (string)strtoupper($customer->Customer->State);
					$arrCustomer['BillingZip'] = (string)$customer->Customer->Zip;
					$arrCustomer['BillingCountry'] = ($customer->Customer->Country != '') ? (string)strtoupper($customer->Customer->Country) : 'US';
					$arrCustomer['ShippingNameFirst'] = (string)ucwords(strtolower($customer->Customer->FirstName));
					$arrCustomer['ShippingNameLast'] = (string)ucwords(strtolower($customer->Customer->LastName));
					$arrCustomer['ShippingAddress'] = (string)ucwords($customer->Customer->Address1);
					$arrCustomer['ShippingAddress2'] = (string)ucwords($customer->Customer->Address2);
					$arrCustomer['ShippingCity'] = (string)ucwords(strtolower($customer->Customer->City));
					$arrCustomer['ShippingState'] = (string)strtoupper($customer->Customer->State);
					$arrCustomer['ShippingZip'] = (string)$customer->Customer->Zip;
					$arrCustomer['ShippingCountry'] = ($customer->Customer->Country != '') ? (string)strtoupper($customer->Customer->Country) : 'US';
					$arrCustomer['Phone'] = (string)$customer->Customer->Phone;
					$arrCustomer['Email'] = (string)strtolower($customer->Customer->Email);
					//print_r($arrCustomer).'<p>';
					// Customer sales (internal)
					$orderID = false;
					foreach ($customer->Sales as $sale) {
						if ($sale->SaleInfo->SaleID != '') {
							$orderID = (string)$sale->SaleInfo->SaleID;
							$arrSale = array();
							$arrSale['OrderID'] = $orderID;
							$arrSale['OrderAgent'] = (string)$OrderAgent;
							$arrSale['OrderCustomer'] = $arrCustomer;
							//$sale->SaleInfo->BillingID;
							$arrSale['OrderDate'] = date("Y-m-d H:i:s",strtotime((string)$sale->SaleInfo->SaleDate));
							//$arrSale['OrderDate'] = (string)str_replace('T',' ',$sale->SaleInfo->SaleDate);
							$arrSale['OrderTotal'] = (string)$sale->SaleInfo->PricePaid;
							$arrSale['OrderTax'] = '';
							$arrSale['OrderShipping'] = '';
							// Order product details
							$arrProducts = array();
							foreach ($sale->SaleInfo->ProductList as $products) {
								$arrProducts[] = array('SKU'=>(string)$products->SaleProduct->SKU,'Quantity'=>(string)$products->SaleProduct->Quantity,'Description'=>'','Price'=>'');
							}
							$arrSale['OrderDetails'] = $arrProducts;
							unset($arrProducts);						
							$arrSale['OrderHasParent'] = '';
							$arrSale['OrderIsUpsell'] = '';
							$arrSale['OrderIsSubscription'] = '';
							$arrSale['OrderSubscriptionBillingCycle'] = '';
							$arrResult[] = $arrSale;
							//print_r($arrSale);
							unset($arrSale);
						}
					}
					// Customer sales (external)
					foreach ($customer->ExternalSales->DirectActionSaleInfo as $sale) {
						if ($sale->DirectActionClubRequestID != '') {
							$arrSale = array();
							$arrSale['OrderID'] = (string)$sale->DirectActionClubRequestID;
							$arrSale['OrderAgent'] = (string)$OrderAgent;
							$arrSale['OrderCustomer'] = $arrCustomer;
							//$sale->SaleInfo->BillingID;
							$arrSale['OrderDate'] = date("Y-m-d H:i:s",strtotime((string)$sale->SaleDate));
							//$arrSale['OrderDate'] = (string)str_replace('T',' ',$sale->SaleDate);
							$arrSale['OrderTotal'] = '';
							$arrSale['OrderTax'] = '';
							$arrSale['OrderShipping'] = '';
							$arrSale['OrderDetails'] = array(array('SKU'=>(string)$sale->UpsellID,'Quantity'=>'1','Description'=>'','Price'=>''));
							$arrSale['OrderHasParent'] = $orderID;
							$arrSale['OrderIsUpsell'] = '1';
							$arrSale['OrderIsSubscription'] = '';
							$arrSale['OrderSubscriptionBillingCycle'] = '';
							$arrResult[] = $arrSale;
							//print_r($arrSale);
							unset($arrSale);
						}
					}
					// Customer sales (subscriptions)
					foreach ($customer->Subscriptions as $subscription) {
						if ($subscription->SubscriptionInfo->PlanID != '') {
							$arrSale = array();
							$arrSale['OrderID'] = (string)$subscription->SubscriptionInfo->PlanID;
							$arrSale['OrderAgent'] = (string)$OrderAgent;
							$arrSale['OrderCustomer'] = $arrCustomer;
							//$sale->SaleInfo->BillingID;
							$arrSale['OrderDate'] = date("Y-m-d H:i:s",strtotime((string)$subscription->SubscriptionInfo->CreateDate));
							//$arrSale['OrderDate'] = (string)str_replace('T',' ',$subscription->SubscriptionInfo->CreateDate);
							$arrSale['OrderTotal'] = '';
							$arrSale['OrderTax'] = '';
							$arrSale['OrderShipping'] = '';
							$arrSale['OrderDetails'] = array(array('SKU'=>(string)$subscription->SubscriptionInfo->PlanID,'Quantity'=>'1','Description'=>'','Price'=>''));
							$arrSale['OrderHasParent'] = (string)$subscription->SubscriptionInfo->InitialSaleID;
							$arrSale['OrderIsUpsell'] = '';
							$arrSale['OrderIsSubscription'] = '1';
							// Calculate billing cycle data
							if ($subscription->SubscriptionInfo->NextBillDate != '') {
								$date_start = strtotime(date(date("Y-m-d H:i:s",strtotime((string)$subscription->SubscriptionInfo->CreateDate))));
								$date_end = strtotime(date(date("Y-m-d H:i:s",strtotime((string)$subscription->SubscriptionInfo->NextBillDate))));
								$arrSale['OrderSubscriptionBillingCycle'] = (string)round(($date_end-$date_start)/(60*60*24));
							}
							else {
								$arrSale['OrderSubscriptionBillingCycle'] = '';
							}
							$arrResult[] = $arrSale;
							//print_r($arrSale);
							unset($arrSale);
						}
					}
					// Clear variables
					$orderID = false;
					unset($arrCustomer);
				}
			}
		}
		else {
			$reportSuccess = false;
			$reportResult = $postStatus;
			$arrResult = false;
		}
		//print_r($arrResult);
		//die();
				
		return $reportSuccess;
		return $reportResult;
		return $arrResult;
	}
	
}
?>