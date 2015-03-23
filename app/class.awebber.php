<?
##########################
#iManage class: awebber
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class awebber {
	
	function addContact ($first_name,$last_name,$email,$list) {
		global $addResponse;
		
		$list = (isset($list) && strlen($list)) ? $list : '0';
		switch ($list) {
			case '0':
				$listName = 'bbtnon-members';
				$listAdTrack = 'BBT_Opt-In_Form';
			break;
			case '1':
				$listName = 'bbtnewmembers';
				$listAdTrack = 'BBT_Members';
			break;
		}
		$listTo = $listName.'@aweber.com';
		$listSubject = '';
		$listMessage = 'Baffle: Yes'."\r\n".'BBT-Signup: Yes'."\r\n".'Name: '.$first_name.' '.$last_name."\r\n".'Email: '.$email."\r\n".'Ad Tracking: '.$listAdTrack;
		$listHeaders = 'From:support@bodybytype.com'."\r\n".'Reply-To:support@bodybytype.com'."\r\n".'X-Mailer:PHP/'.phpversion();
		@mail($listTo, $listSubject, $listMessage, $listHeaders);
		$addResponse = $listHeaders."\r\n\r\n".$listMessage;
		
		return $addResponse;
	}

}
?>