<?php 

include("settings.php");
include("utilities.php");

$imagefolder=$_SERVER['IMAGE_ROOT'].$_SERVER['ALBUM_FOLDER'];
$thumbsfolder=$_SERVER['THUMB_ROOT'].$_SERVER['ALBUM_FOLDER'];

echo "<a href='".currentURL()."/view_album.php'>Home</a>";
if ($_SERVER['ALBUM_FOLDER'] != "" ) {
	echo " &gt; ";
	displayHeirarchy($_SERVER['ALBUM_FOLDER']);
}

echo "<br/><br/>";

if (!isset($image)) {
	echo "Please specify an Image!";
} else {
	displayImage($imagefolder, $image, $thumbsfolder, $intermediatesize, TRUE);
}

?>

