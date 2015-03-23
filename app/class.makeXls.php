<?
##########################
#iManage class: makeXls
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class makeXls {
	
	function xlsOpen() {
		global $xlsSheet;
		
		$xlsSheet = pack('ssssss', 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
		
		return $xlsSheet;
	}
	
	function xlsClose() {
		global $xlsSheet;
		
		$xlsSheet .= pack('ss', 0x0A, 0x00);
		
		return $xlsSheet;
	}
	
	function xlsHeader($headerRow,$headerCol,$headerValue) { 
		global $xlsSheet;
		
		$xlsSheet .= pack('ssssss', 0x204, 8 + strlen($headerValue), $headerRow, $headerCol, 0x11, strlen($headerValue)); 
		$xlsSheet .= $headerValue; 
		
		return $xlsSheet; 
	}

	function xlsNum($dataRow,$dataCol,$dataValue) { 
		global $xlsSheet;
		
		$xlsSheet .= pack('sssss', 0x203, 14, $dataRow, $dataCol, 0x0); 
		$xlsSheet .= pack('d', $dataValue);

		
		return $xlsSheet;
	} 

	function xlsText($headerRow,$headerCol,$headerValue) { 
		global $xlsSheet;
		
		$xlsSheet .= pack('ssssss', 0x204, 8 + strlen($headerValue), $headerRow, $headerCol, 0x0, strlen($headerValue)); 
		$xlsSheet .= $headerValue; 
		
		return $xlsSheet; 
	}
	
}
?>