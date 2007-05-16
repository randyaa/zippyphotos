<?php 

/* flag to determine if the thumbnails will be stored within the image directory.  this is most typically done when the images/albums exist at the root. */
$thumbsinsideimageroot=TRUE;

/* maximum thumbnail size.  the generated thumbnail will fit within a square specified by this size. */
$thumbsize = 200;

/* maximum intermediate size.  the generated image will fit within a squre specified by this size. */
$intermediatesize = 800;

/* the maximum number of images to display in each row. */
$imagesperrow = 3;

/* the maximum number of rows to display on each page. */
$rowsperpage = 3;

/* the root location of the gallery.  The image folder and thumb folder will be below this gallery root */
$_SERVER['GALLERY_ROOT'] = "";

$_SERVER['IMAGE_ROOT'] = "";

/* the location of all the images/albums.  This will be below the gallery root.
$_SERVER['IMAGE_ROOT'] = "";

/* the location in which to place the thumbnails.  this location will essentially mirror the structure of the album/photo root.*/
$_SERVER['THUMB_ROOT'] = 'newthumbs/';

/* TODO fix this.  this forces two locations to set the thumbnail directory.  BAD!! */
if ($thumbsinsideimageroot) {
	$_SERVER['THUMB_ALBUM'] = 'newthumbs';
}

/* obtain the album from the query string */
if (isset($_GET['album'])){
	/*$albumfolder */
	$_SERVER['ALBUM_FOLDER'] = $_GET['album'];
} else {
	$_SERVER['ALBUM_FOLDER'] = "";
}
/* obtain the image from the query string */
if (isset($_GET['image'])) {
	$image = $_GET['image'];
}

/* specify the order in which albums should be displayed. */
$_SERVER['NEW_ALBUM_LOCATION'] = 'top';

/* RSS settings */
$_SERVER['WEBSITE_TITLE'] = "Randy and Rachel's Photos";
$_SERVER['WEBSITE_DESCRIPTION'] = "Randy and Rachel's Photos";
$_SERVER['RSS_FILE'] = "RSS.txt";


?>