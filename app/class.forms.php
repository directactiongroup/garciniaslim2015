<?
##########################
#iManage class: forms
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class forms {

	// Embed HTML tag tools
     function tagTools($styles,$link,$formTarget) {
     	global $skinsDir;
		global $adminSiteURL;
		global $uncatNewsBlogPhotosID;
	
		if ($styles == 1) {
			echo '<img src="skins/'.$skinsDir.'/images/content_styles.gif" class="image_tagTitle" /><br /><input type="button" onclick="addTag(\'strong\',document.contentForm.'.$formTarget.'.name);" class="form_tagBold" /><input type="button" onclick="addTag(\'i\',document.contentForm.'.$formTarget.'.name);" class="form_tagItalic" /><input type="button" onclick="addTag(\'u\',document.contentForm.'.$formTarget.'.name);" class="form_tagUnderline" />';
		}
		if ($link == 1) {
			echo '<br /><img src="skins/'.$skinsDir.'/images/content_link.gif" class="image_tagTitle" /><br /><textarea id="textLink" name="link" class="form_textLink"></textarea><br /><font class="text_contentForm">New Window:</font> <input id="newWindow" type="checkbox" name="new" value="1" /><br /><input type="button" onclick="addLink(textLink.value,document.contentForm.'.$formTarget.'.name);" class="form_tagLink" /><br /><img src="skins/'.$skinsDir.'/images/content_image.gif" class="image_tagTitle" /><br /><a href="#" onclick="document.getElementById(\'embedImg\').style.visibility = \'visible\';"><img src="skins/'.$skinsDir.'/images/button_embed_img.png" class="image_tagTitle" /></a>';
			// Build image selector
			echo '<div id="embedImg" class="content_imageSelect"><div class="content_imageSelect_close"><a href="#" onclick="document.getElementById(\'embedImg\').style.visibility = \'hidden\';"><img src="skins/'.$skinsDir.'/images/button_image_close.png" class="image_noborders" /></a></div>';
			$databaseConnect = database::dbConnection();
			$databaseSelect = database::dbConnection();
			// Get selected images
			$dbResult = mysql_query('SELECT a.uploads_directory,b.photos_images_name FROM uploads a LEFT JOIN photos_images b ON a.uploads_id = b.photos_images_uploads_id LEFT JOIN photos c ON b.photos_images_photos_id = c.photos_id WHERE c.photos_id = '.$uncatNewsBlogPhotosID.' ORDER BY b.photos_images_id DESC',$databaseConnect);
			$rowIndex = 1;
			while ($dbRow = mysql_fetch_array($dbResult)) {
				echo '<div class="layout_formDetail"><a href="#" onclick="embedPhoto(\''.$dbRow['uploads_directory'].'/'.$dbRow['photos_images_name'].'\');"><img src="'.$adminSiteURL.'/'.$dbRow['uploads_directory'].'/'.$dbRow['photos_images_name'].'" class="image_photoAdd" /></a></div>';
				if ($rowIndex == 4) {
					echo '<div class="layout_row"></div>';	
					$rowIndex = 0;
				}
				$rowIndex++;
			}
			$databaseClose = database::dbClose();
			echo '</div>';
		}
	
	}

}
?>
