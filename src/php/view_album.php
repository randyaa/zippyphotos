<?php 


include("settings.php");
include("utilities.php");

echo "<a href='".currentScript()."'>home</a>";

if (isset($_GET['album'])){
	$albumfolder = $_GET['album'];
} else {
	$albumfolder = "";
}

$imagefolder=$imageroot.$albumfolder;
$thumbsfolder=$thumbsroot.$albumfolder;

if ($albumfolder != "" ) {
	echo " &gt; ";
	displayHeirarchy($albumfolder);
}

echo "<br/><br/>";

displayDirectories($imagefolder, $albumfolder);

echo "<br/>";

displayPageLinks($imagefolder, $rowsperpage, $imagesperrow);

displayThumbs($imagefolder, $thumbsfolder, $thumbsize, $imagesperrow, $rowsperpage);

displayPageLinks($imagefolder, $rowsperpage, $imagesperrow);

echo "<br/><br/>";

echo "<a href='".currentScript()."'>home</a>";

if ($albumfolder != "" ) {
	echo " &gt; ";
	displayHeirarchy($albumfolder);
}

?>
