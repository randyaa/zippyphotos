<?php 

include("settings.php");
include("utilities.php");

echo "<a href='".currentScript()."'>home</a>";

if (isset($_GET['album'])){
	$albumfolder = $_GET['album'];
} else {
	$albumfolder = "";
}
$image = $_GET['image'];

$imagefolder=$imageroot.$albumfolder;
$thumbsfolder=$thumbsroot.$albumfolder;

if ($albumfolder != "" ) {
	echo " &gt; ";
	displayHeirarchy($albumfolder);
}
echo "<br/><br/>";

displayImage($imagefolder, $image, $thumbsfolder, $intermediatesize, TRUE);

?>

