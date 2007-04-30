<?php 

include("settings.php");
include("utilities.php");


if (isset($_GET['album'])){
	$albumfolder = $_GET['album'];
} else {
	$albumfolder = "";
}
if (isset($_GET['image'])) {
	$image = $_GET['image'];
}

$imagefolder=$imageroot.$albumfolder;
$thumbsfolder=$thumbsroot.$albumfolder;

echo "<a href='".currentURL()."/view_album.php'>home</a>";
if ($albumfolder != "" ) {
	echo " &gt; ";
	displayHeirarchy($albumfolder);
}
echo "<br/><br/>";

if (!isset($image)) {
	echo "Please specify an Image!";
} else {
	displayImage($imagefolder, $image, $thumbsfolder, $intermediatesize, TRUE);
}

?>

