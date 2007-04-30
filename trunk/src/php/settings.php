<?php 

$galleryroot= '';
$imageroot= '';
$thumbsroot = 'newthumbs/';
$thumbsinsideimageroot=TRUE;
$thumbsize = 200;
$intermediatesize = 800;
$imagesperrow = 3;
$rowsperpage = 3;

$_SERVER['GALLERY_ROOT'] = $galleryroot;
$_SERVER['IMAGE_ROOT'] = $imageroot;
$_SERVER['THUMB_ROOT'] = $thumbsroot;

/* TODO fix this.  this forces two locations to set the thumbnail directory.  BAD!! */
if ($thumbsinsideimageroot) {
	$_SERVER['THUMB_ALBUM'] = 'newthumbs';
}


?>