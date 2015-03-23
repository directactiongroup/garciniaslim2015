<?
##########################
#iManage class: cleaner
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class cleaner {
	
	// SQL stripper
	function cleanSQL($string) {
		
		return preg_replace('/on[a-z]+=\".*\"/i','', $string);
		
	}
	
	// Boolean only
	function cleanBool($string) {
		
		return trim(preg_replace('/[^10]/','',$this->cleanSQL($string)));
		
	}
	
	// Numeric only
	function cleanNum($string) {
		
		return trim(preg_replace('/[^0-9.\-]/','',$this->cleanSQL($string)));
		
	}
	
	// Interger only
	function cleanInt($string) {
		
		return trim(preg_replace('/[^0-9]/','',$this->cleanSQL($string)));
		
	}
	
	// Alphabetical only
	function cleanAlpha($string) {
		
		return trim(preg_replace('/[^a-zA-Z]/','',$this->cleanSQL($string)));
		
	}

	// Alphabetical lowercase only
	function cleanAlphaLower($string) {
		
		return trim(preg_replace('/[^a-z_]/','',$this->cleanSQL($string)));
		
	}

	// Alphabetical uppercase only
	function cleanAlphaUpper($string) {
		
		return trim(preg_replace('/[^A-Z_]/','',$this->cleanSQL($string)));
		
	}
	
	// Alphanumeric only
	function cleanAlphaNum($string) {
		
		return trim(preg_replace('/[^0-9a-zA-Z_.\-]/','',$this->cleanSQL($string)));
		
	}
	
	// Directory format only
	function cleanDirName($string) {
		
		return trim(preg_replace('/[^0-9a-zA-Z_.\/\-]/','',$this->cleanSQL($string)));
		
	}
	
	// Email format
	function cleanEmail($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9@.!#$%&\'*+-\/=?^_`{|}~]/','',$this->cleanSQL($string)));
		
	}
	
	// Proper name format
	function cleanName($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9.:,!?#\/\-\' ]/','',$this->cleanSQL($string)));
		
	}
	
	// Accented name format
	function cleanNameAcc($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9.:,!?\/\-\'\À\Á\Ã\Ä\Ç\È\É\Ë\Ì\Í\Ï\Ñ\Ò\Ó\Ö\Ø\Ú\Ü\Ý\Ÿ\?\à\á\ã\ä\ç\è\é\ë\ì\í\ï\ñ\ò\ó\õ\ö\ø\ù\ú\ü\ý\ß ]/','',$this->cleanSQL($string)));
		
	}
	
	// Proper name format
	function cleanNameFormat($string) {
		
		return ucwords(strtolower(trim(preg_replace('/[^a-zA-Z0-9.:,!?#\/\-\' ]/','',$this->cleanSQL($string)))));
		
	}
	
	// Special characters format
	function cleanSpecial($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9.:;,@#$%*+=_()[]{}&!?|<>\/\-\' ]/','',$this->cleanSQL($string)));
		
	}
	
	// Username / password format
	function cleanCredentials($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9.:;,@#$%*_&!?\/\-\']/','',$this->cleanSQL($string)));
		
	}
	
	// Proper name format
	function cleanDate($string) {
		
		return trim(preg_replace('/[^0-9a-zA-Z:,\/\- ]/','',$this->cleanSQL($string)));
		
	}
	
	// Zip code format
	function cleanZip($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9\- ]/','',$this->cleanSQL($string)));
		
	}
	
	// Text with html tags
	function cleanTags($string) {
		
		return trim(preg_replace('/[^a-zA-Z0-9.:;,@#$%*+=_()[]{}&!?<>\/\-\'\À\Á\Ã\Ä\Ç\È\É\Ë\Ì\Í\Ï\Ñ\Ò\Ó\Ö\Ø\Ú\Ü\Ý\Ÿ\?\à\á\ã\ä\ç\è\é\ë\ì\í\ï\ñ\ò\ó\õ\ö\ø\ù\ú\ü\ý\ß ]/','',str_replace(array("\n","\r\n","\r"),"",nl2br($this->cleanSQL($string)))));		
	
	}
	
	// Full punctuation format
	function cleanText($string) {
		
		return trim(stripslashes($this->cleanSQL($string)));
		
	}
	
	// Replace accented characters
	function cleanAccents($string) {
	
		return strtr($this->cleanSQL($string),'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ','SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
	
	}
	
	// Convert <br /> tags to new line
	function cleanBR2NL($string) {
		
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n",$string);
	}
	
}
?>