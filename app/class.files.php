<?
##########################
#iManage class: files
#Author: Eric Swenson
#Copyright 2010 Secret Lab Studio, LLC
#Unauthorized use or distriibution of iManage or it's components is forbidden without the permission of Secret Lab Studio, LLC
##########################

class files {
	
	// Generate filename
	function fileName() {
	
		return rand(1111111111,9999999999);
		
	}

	// Create resized file
	function createJpg($targetFile,$createFile,$newWidth,$newHeight,$aspectClip) {

		$imgExt = substr($targetFile,(strrpos($targetFile,'.')+1),3);
		list($srcWidth,  $srcHeight) = getimagesize($targetFile);
		if ($newWidth == '' && $newHeight == '') {
			$newWidth = $srcWidth;	
			$newHeight = $srcHeight;
		}
		if ($newWidth > $srcWidth) {
			$newWidth = $srcWidth;	
		}
		if ($newHeight > $srcHeight) {
			$newHeight = $srcHeight;	
		}
		$newX = 0;
		$newY = 0;
		$crop = 0;
		$cropWidth = $newWidth;
		$cropHeight = $newHeight;
		$cropX = 0;
		$cropY = 0;
		$distortion = .2;
		// Calculate new image size
		if ($newWidth > $newHeight) {
			$aspectHeight = round($srcHeight*($newWidth/$srcWidth));
			if ($newHeight == '' || $srcWidth < $srcHeight) {
				$newHeight = $aspectHeight;
			}
		}
		else if ($newWidth < $newHeight) {
			$aspectWidth = round($srcWidth*($newHeight/$srcHeight));
			if ($newWidth == '' || $srcWidth > $srcHeight) {
				$newWidth = $aspectWidth;
			}
		}
		// Calculate aspect ratio to determine whether to crop
		if ($aspectClip == 1) {
			if ($newHeight > $newWidth && $aspectWidth && $aspectWidth < $newWidth) {
				$aspectHeight = $newHeight+($newWidth-$aspectWidth);
				$aspectWidth = $newWidth;
			}
			if ($newWidth > $newHeight && $aspectHeight && $aspectHeight < $newHeight) {
				$aspectWidth = $newWidth+($newHeight-$aspectHeight);
				$aspectHeight = $newHeight;
			}
			if (round($aspectHeight+($aspectHeight*$distortion)) > $newHeight) {
				$crop = 1;
				$cropY = round(($aspectHeight-$newHeight)/2);
				$newHeight = $aspectHeight;

			}
			if (round($aspectWidth+($aspectWidth*$distortion)) > $newWidth) {
				$crop = 1;
				$cropX = round(($aspectWidth-$newWidth)/2);
				$newWidth = $aspectWidth;

			}
		}
		// Create new image
		$newImg = imagecreatetruecolor($newWidth, $newHeight);
		if ($imgExt == 'gif') {
			$srcImg = imagecreatefromgif($targetFile);
		}
		else if ($imgExt == 'png') {
			$srcImg = imagecreatefrompng($targetFile);
		}
		else {
			$srcImg = imagecreatefromjpeg($targetFile);
		}
		imagecopyresampled($newImg, $srcImg, 0, 0, $newX, $newY, $newWidth, $newHeight, $srcWidth, $srcHeight);
		imagejpeg($newImg, $createFile, 100);
		imagedestroy($newImg);
		// Crop new image
		if ($crop == 1 && file_exists($createFile)) {
			$cropImg = imagecreatetruecolor($cropWidth, $cropHeight);
			$srcImg = imagecreatefromjpeg($createFile);
			imagecopyresampled($cropImg, $srcImg, 0, 0, $cropX, $cropY, $newWidth, $newHeight, $newWidth, $newHeight);
			imagejpeg($cropImg, $createFile, 100);
			imagedestroy($cropImg);
		}
	
	}

}
?>