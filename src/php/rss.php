<? 
include("settings.php");
include("settings.php");

// get your news items from somewhere, e.g. your database:
$myFile = "RSS.txt";
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);


echo "<rss version='2.0'>";
echo "<channel>";
echo "<title>Randy and Rachel's Photos</title>";
echo "<link>".currentURL()."</link>";
echo "<description>Randy and Rachel's Photos.</description>";

preg_match_all ("/<album>.*<name>(.*)<\/name>.*<link>(.*)<\/link>.*<\/album>/", $theData, $matches, PREG_SET_ORDER);

$reversedmatches = array_reverse($matches);

foreach ($reversedmatches as $val) {
	writeItem($val[1],$val[2],$val[2]);	
}

echo "</channel>";
echo "</rss>";

function writeItem($title, $link, $description) {
	echo "<item>";
	echo "<title>".$title."</title>";
	echo "<link>".$link."</link>";
	echo "<description>".$description."</description>";
	echo "</item>";
}


?>