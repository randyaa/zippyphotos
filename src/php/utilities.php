<?php 

function addAlbumRecord($thumbnaildir) {
	$albumname = substr($thumbnaildir, strlen($_SERVER['THUMB_ROOT']));  // abcdef
	$link = currentURL()."/view_album.php?album=".$albumname;
	$File = $_SERVER['RSS_FILE'];
	$Handle = fopen($File, 'a');
	$Data = "<album><name>".$albumname."</name><link>".$link."</link></album>\n";
	fwrite($Handle, $Data); 
	fclose($Handle); 
}

/*
 */
function displayDirectories($imagefolder, $currentalbum) {
	$i = 0;
	
	if ($handle = opendir($imagefolder)) {
		while (($file = readdir($handle)) !== false) {
			/* if the file is a directory */
			if (is_dir($imagefolder."/".$file)) {
				/* if the file is not . or .. (current directory or parent directory */
				if ($file !="." && $file !="..") {
					$newalbum = $currentalbum;
					if ($currentalbum != "") {
						$newalbum .= "/";
					}
					$newalbum .= $file;
					
					if (!isset($_SERVER['THUMB_ALBUM']) || $_SERVER['THUMB_ALBUM'] != $newalbum) {
						$albumarray[$i] = $newalbum;
						$filearray[$i] = $file;
						$i = $i + 1;
					}
				}
			}
		}
		
		if (isset($filearray)) {
			sort($albumarray);
			sort($filearray);
			if ($_SERVER['NEW_ALBUM_LOCATION'] == "bottom") {
				for ( $counter = 0; $counter < count($filearray); $counter += 1) {
					echo "<a href='".currentScript()."?album=".$albumarray[$counter]."'>".$filearray[$counter]."</a><br/>";		
				}
			} else {
				/* by default display the newest album at the top */
				for ( $counter = count($filearray)-1; $counter >= 0; $counter -= 1) {
					echo "<a href='".currentScript()."?album=".$albumarray[$counter]."'>".$filearray[$counter]."</a><br/>";		
				}	
			}
		}
	}
}

function getFileName($path) {
	
}

function currentScript() {
	return currentURL().$_SERVER['SCRIPT_NAME'];
}

function currentQuery() {
	return currentScript()."?".$_SERVER['QUERY_STRING'];
}

function displayPageLinks($imagefolder, $rowsperpage, $imagesperrow) {
	$imagecount = 0;
	if ($handle = opendir($imagefolder)) {
		while (($file = readdir($handle)) !== false) {
			if (!is_dir($imagefolder."/".$file) && $file != "Thumbs.db") {
				$imagecount = $imagecount + 1;
			}
		}
	}
	$tpagecount = $imagecount / ($rowsperpage * $imagesperrow);
	$pagecount = 0;
	for ($i=0; $i < $tpagecount; $i+= 1 ) {
		$pagecount += 1;
	}
	if ($pagecount > 1 ) {
		for ($i=0; $i < $pagecount; $i += 1) {
			echo " <a href='".currentQuery()."&page=".($i+1)."'>".($i+1)."</a> ";
		}
		echo " <a href='".currentQuery()."&page=all'>all</a>";
	}
}

/*
 * display images.
 */
function displayThumbs($imagefolder, $thumbsfolder, $maxthumbnailsize, $imagesperrow, $rowsperpage){

	
	if (!isset($_GET['page'])) {
		$page="1";
	} else {
		$page=$_GET['page'];
	}
	
	if ($page=="all"){
		$lastpic=9999999999999;
		$pictostartat=0;
	}else {
		$lastpic=($rowsperpage*$imagesperrow)*$page;	
		$pictostartat=$lastpic-($rowsperpage*$imagesperrow);
	}

	$pics=directory($imagefolder,"jpg,JPG,JPEG,jpeg,png,PNG");
	$maxsize=$maxthumbnailsize;

	if (isset($pics[0]) && $pics[0]!="") {
		echo "<table><tr>";
		$COUNT = 0;
		foreach ($pics as $p) {
			if ($COUNT < $pictostartat) {
				$COUNT = $COUNT + 1;
				continue;
			} else if ($COUNT >= $lastpic) {
				$COUNT = $COUNT + 1;
				continue;
			} else {
				$COUNT = $COUNT + 1;
			}

			
			displayImage($imagefolder, $p,$thumbsfolder,$maxsize, FALSE);
			if ($COUNT % $imagesperrow == 0 ) {
				echo "</tr><tr>";
			}
		}
		echo "</tr></table>";
	}
}

function displayImage($imagefolder, $imagefilename,$thumbsfolder,$maxsize, $linktooriginal){
	$originalfile=$imagefolder."/".$imagefilename;
	$thumbnailfile=$thumbsfolder."/tn_".$maxsize."_".$imagefilename;
	if (!file_exists($thumbnailfile)) {
		createthumb($originalfile,$thumbnailfile,$maxsize,$maxsize);
	}
	echo "<td><a href='";
	if (!$linktooriginal) {
		echo currentURL()."/".$_SERVER['GALLERY_ROOT']."view_image.php?album=".$_GET['album']."&image=".$imagefilename;
	} else {
		echo $originalfile;
	}
	echo "'><img src='".$thumbnailfile."'/></a></td>";
}

/*
 * generate links to all the parent albums for the current album.
 */
function displayHeirarchy( $albumfolder ){
	
	$heirarchy=split('/',$albumfolder);

	$runningalbum="";
	$first = TRUE;
	foreach ( $heirarchy as $album ) {
		if (!$first){
			$runningalbum.="/";
		} else {
			$first = FALSE;
		}
		$runningalbum.=$album;
		echo "<a href='".currentURL()."/".$_SERVER['GALLERY_ROOT']."view_album.php?album=".$runningalbum."'> ".$album."</a> &gt; ";
	}
}

/*
 * Don't worry about removing thumbnails.  there wont be any and even
 * if there is we should just re-generate a thumbnail in the
 * appropriate  directory.
 * 
 * $pics=ditchtn($pics,"tn_");
 */
/*
 *	Function ditchtn($arr,$thumbname)
 *	filters out thumbnails
 */
function ditchtn($arr,$thumbname) {
	foreach ($arr as $item)	{
		if (!preg_match("/^".$thumbname."/",$item)) {
            	$tmparr[]=$item;
		}
	}
	return $tmparr;
}



/*
 *    Function directory($directory,$filters)
 *  	reads the content of $directory, takes the files that apply to
 * 	$filter 
 *	and returns an array of the filenames.
 *    You can specify which files to read, for example
 *    $files = directory(".","jpg,gif");
 *            gets all jpg and gif files in this directory.
 *    $files = directory(".","all");
 *            gets all files.
 */
function directory($dir,$filters) {
	if ($dir == "") {
		$directory = "./";
	} else {
		$directory = $dir;
	}
	$handle=opendir($directory);
	$files=array();

	if ($filters == "all") {
		while(($file = readdir($handle))!==false) {
			$files[] = $file;
		}
	}

	if ($filters != "all") {
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false) {
			for ($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if (!is_dir($dir."/".$system[0])) {
					if (isset($system[1]) && $system[1] == $filters[$f]) {
						$files[] = $file;
					}
				}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}

/*
 * get the current url.
 */
function currentURL() {
	$_SERVER['FULL_URL'] = 'http';
	if($_SERVER['HTTPS']=='on'){
		$_SERVER['FULL_URL'] .=  's';
	}
	
	$_SERVER['FULL_URL'] .=  '://';
	
	if($_SERVER['SERVER_PORT']!='80') {
		$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
	} else {
		$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'];
/*.$_SERVER['SCRIPT_NAME'];*/
	}
	/*if($_SERVER['QUERY_STRING']>' ') {
		$_SERVER['FULL_URL'] .=  '?'.	$_SERVER['QUERY_STRING'];
	}*/
	return $_SERVER['FULL_URL'];
}

/*
 * Recursively make all parent directories.
 */
function MakeDirectory($dir, $mode) {
  	$madeit = @mkdir($dir, $mode);
  	if (is_dir($dir) || $madeit){
  		if ($madeit) {
  			addAlbumRecord($dir);
  		}
  		return TRUE;
  	}
  	
  	if (!MakeDirectory(dirname($dir),$mode)){
  		return FALSE;
  	}
  	
  	$newdir = @mkdir($dir,$mode);
  	if ($newdir) {
  		addAlbumRecord($dir);
  	}
  	return $newdir;
}

/*
 *	Function createthumb($name,$filename,$new_w,$new_h)
 *	creates a resized image
 *	variables:
 *	$name		Original filename
 *	$filename	Filename of the resized image
 *	$new_w		width of resized image
 *	$new_h		height of resized image
 */	
function createthumb($name,$filename,$new_w,$new_h) {

	if (file_exists($name)) {
		if(!MakeDirectory(dirname($filename), 0777,true)) {
			echo "Error: ".$thumbsfolder." Does not exist and could not be created.<BR>";
		}
	} else {
		echo $name." does not exist.  Cannot generate thumbnails.";
		return;
	}
	
	$system=explode(".",$name);
	$src_img="";
	if (preg_match("/jpg|jpeg|JPG|JPEG/",$system[1])) {
		$src_img=imagecreatefromjpeg($name);
	}
	if (preg_match("/png|PNG/",$system[1])) {
		$src_img=imagecreatefrompng($name);
	}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	
	if ($old_x > $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) 
	{
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	fastimagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	/*imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); */
	if (preg_match("/png/",$system[1])) {
		imagepng($dst_img,$filename); 
	} else {
		imagejpeg($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

function fastimagecopyresampled (&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 2) {
  // Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
  // Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
  // Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
  // Author: Tim Eckel - Date: 12/17/04 - Project: FreeRingers.net - Freely distributable.
  //
  // Optional "quality" parameter (defaults is 3).  Fractional values are allowed, for example 1.5.
  // 1 = Up to 600 times faster.  Poor results, just uses imagecopyresized but removes black edges.
  // 2 = Up to 95 times faster.  Images may appear too sharp, some people may prefer it.
  // 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled.
  // 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
  // 5 = No speedup.  Just uses imagecopyresampled, highest quality but no advantage over imagecopyresampled.

  if (empty($src_image) || empty($dst_image)) { return false; }
  if ($quality <= 1) {
    $temp = imagecreatetruecolor ($dst_w + 1, $dst_h + 1);
    imagecopyresized ($temp, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w + 1, $dst_h + 1, $src_w, $src_h);
    imagecopyresized ($dst_image, $temp, 0, 0, 0, 0, $dst_w, $dst_h, $dst_w, $dst_h);
    imagedestroy ($temp);
  } elseif ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
    $tmp_w = $dst_w * $quality;
    $tmp_h = $dst_h * $quality;
    $temp = imagecreatetruecolor ($tmp_w + 1, $tmp_h + 1);
    imagecopyresized ($temp, $src_image, $dst_x * $quality, $dst_y * $quality, $src_x, $src_y, $tmp_w + 1, $tmp_h + 1, $src_w, $src_h);
    imagecopyresampled ($dst_image, $temp, 0, 0, 0, 0, $dst_w, $dst_h, $tmp_w, $tmp_h);
    imagedestroy ($temp);
  } else {
    imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
  }
  return true;
}
