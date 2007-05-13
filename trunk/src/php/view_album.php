<?php 

include("settings.php");
include("utilities.php");

$imagefolder=$_SERVER['IMAGE_ROOT'].$_SERVER['ALBUM_FOLDER'];
$thumbsfolder=$_SERVER['THUMB_ROOT'].$_SERVER['ALBUM_FOLDER'];

if ($imagefolder == "" ) {
	$imagefolder=".";
}

echo "<a href='".currentScript()."'>home</a>";
if ($_SERVER['ALBUM_FOLDER'] != "" ) {
	echo " &gt; ";
	displayHeirarchy($_SERVER['ALBUM_FOLDER']);
}

echo "<br/><br/>";

displayDirectories($imagefolder, $_SERVER['ALBUM_FOLDER']);

echo "<br/>";

displayPageLinks($imagefolder, $rowsperpage, $imagesperrow);

displayThumbs($imagefolder, $thumbsfolder, $thumbsize, $imagesperrow, $rowsperpage);

displayPageLinks($imagefolder, $rowsperpage, $imagesperrow);


/*echo "<br/><br/>";
echo "<a href='".currentScript()."'>home</a>";
if ($albumfolder != "" ) {
	echo " &gt; ";
	displayHeirarchy($albumfolder);
}*/

?>
