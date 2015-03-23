<?
##########################
#iManage class: mailer
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class mailer {
	
	// Send formatted email
	function sendMail($recipient,$subject,$message,$styleFile) {
		global $mailEmail;
		global $mailSender;
		global $mailResponse;
	
		// Build header
		$mailHeader = "From: ".$mailSender." <".$mailEmail.">\r\nReply-To: ".$mailEmail;
		$mailHeader .= "\r\nMIME-Version: 1.0";
		$mailHeader .= "\r\nContent-Type: text/html; charset=\"iso-8859-1\"";
		// Embed as html if not already
		if (strstr($message,'<html')) {
			$mailMessage .= $message;
		}
		else {
			// Get mail stylesheet
			if (strlen($styleFile)) {
				$styleHandle = fopen($styleFile, 'r');
				$mailStyle = fread($styleHandle, filesize($styleFile));
				fclose($styleHandle);
			}
			$mailMessage .= '<html>';
			$mailMessage .= '<head>';
			$mailMessage .= '<title>'.$subject.'</title>';
			if (strlen($styleFile)) {
				$mailMessage .= '<style type="text/css">'.$mailStyle.'</style>';
			}
			$mailMessage .= '</head>';
			$mailMessage .= '<body>';
			$mailMessage .= '<p>'.$message.'</p>';
			$mailMessage .= '</body>';
			$mailMessage .= '</html>';
		}
		// Place body in output buffer
		$mailMessage = str_replace('\t','',trim($mailMessage));
		// Send email
		$mail_sent = @mail($recipient,$subject,str_replace(chr(13),'',$mailMessage),$mailHeader);

	}
	
	// Send raw email with no preset formatting
	function sendRawMail($senderName,$senderEmail,$recipient,$subject,$message) {
		global $mailResponse;
	
		// Build header
		$mailHeader = "From: ".$senderName." <".$senderEmail.">\r\nReply-To: ".$senderEmail;
		$mailHeader .= "\r\nMIME-Version: 1.0";
		$mailHeader .= (strstr($message,'<html')) ? "\r\nContent-Type: text/html; charset=\"iso-8859-1\"" : "\r\nContent-Type: text/plain; charset=\"iso-8859-1\"";
		// Place body in output buffer
		$mailMessage = str_replace('\t','',trim($message));
		// Send email
		$mail_sent = @mail($recipient,$subject,str_replace(chr(13),'',$mailMessage),$mailHeader);

		return $mailResponse;
	}
	
	// Send email with attachment
	function sendMailAttachment($recipient,$cc_recipient,$subject,$message,$styleFile,$attachment) {
		global $mailEmail;
		global $mailResponse;
		
		// File types
		$mimeArray = array(
		'gif'=>'image/gif',
		'jpg'=>'image/jpeg',
		'png'=>'image/png',
		'tif'=>'image/tiff',
		'tiff'=>'image/tiff',
		'bmp'=>'image/bmp',
		'psd'=>'application/octet-stream',
		'ai'=>'application/postscript',
		'txt'=>'text/plain',
		'csv'=>'text/csv',
		'htm'=>'text/html',
		'html'=>'text/html',
		'xml'=>'text/xml',
		'zip'=>'application/zip',
		'dmg'=>'application/octet-stream',
		'tar'=>'application/x-tar',
		'pdf'=>'application/pdf',
		'avi'=>'video/x-msvideo',
		'mov'=>'video/quicktime',
		'mpg'=>'video/mpeg',
		'mpeg'=>'video/mpeg',
		'mp3'=>'audio/mpeg',
		'mp4'=>'video/mp4',
		'flv'=>'video/x-flv',
		'doc'=>'application/msword',
		'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'ppt'=>'application/vnd.ms-powerpoint',
		'pptx'=>'vnd.openxmlformats-officedocument.presentationml.presentation',
		'xls'=>'application/vnd.ms-excel',
		'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);
		$arrAttach = (!empty($attachment)) ? explode(',',$attachment) : false;
		$semiRand = md5(time()); 
		$mimeBoundary = "==Multipart_Boundary_x{$semiRand}x";
		$eol = "\n";
		// Build header
		$mailHeader = "From: ".$mailEmail.$eol;
		if ($cc_recipient) {
			$mailHeader .= "Cc: ".$cc_recipient.$eol;
		}
		$mailHeader .= "MIME-Version: 1.0".$eol. 
		"Content-Type: multipart/mixed;".$eol. 
		" boundary=\"{$mimeBoundary}\"";  
		$mailMessage .= "This is a multi-part message in MIME format.".$eol.$eol.
		"--{$mimeBoundary}".$eol.
		"Content-Type:text/html; charset=\"iso-8859-1\"".$eol.
		"Content-Transfer-Encoding: 7bit".$eol.$eol;
		// Embed as html if not already
		if (strstr($message,'<html')) {
			$mailMessage .= $message.$eol;
		}
		else {
			// Get mail stylesheet
			if (strlen($styleFile)) {
				$styleHandle = fopen($styleFile, 'r');
				$mailStyle = fread($styleHandle, filesize($styleFile));
				fclose($styleHandle);
			}
			$mailMessage .= "<html>".$eol;
			$mailMessage .= "<head>".$eol;
			$mailMessage .= "<title>".$subject."</title>".$eol;
			if (strlen($styleFile)) {
				$mailMessage .= "<style type=\"text/css\">".$mailStyle."</style>";
			}
			$mailMessage .= "</head>".$eol;
			$mailMessage .= "<body>".$eol;
			$mailMessage .= "<p>".$message."</p>".$eol;
			$mailMessage .= "</body>".$eol;
			$mailMessage .= "</html>".$eol.$eol;
		}
		// Add attachments
		if (!empty($arrAttach)) {
			foreach($arrAttach as $att){
				$mailMessage .= "--{$mimeBoundary}".$eol;
				$attachmentName = substr($att,strrpos($att,'/')+1);
				$attachmentExt = strtolower(substr($att,strrpos($att,'.')+1));
				$attachmentType = $mimeArray[$attachmentExt];
				$attachmentEmbed = chunk_split(base64_encode(file_get_contents($att)));
				$mailMessage .= "Content-Type: {$attachmentType};".$eol. 
				"Content-ID: <".$attachmentName.">".$eol.
				" name=\"{$attachmentName}\"".$eol. 
				"Content-Transfer-Encoding: base64".$eol.$eol. 
				$attachmentEmbed.$eol.$eol;
			}
			$mailMessage .= "--{$mimeBoundary}--".$eol;
		}
		//echo $mailMessage;
		//die();
		// Send email
		$mail_sent = @mail($recipient,$subject,$mailMessage,$mailHeader);
	
		return $mailResponse;
	}

}
?>