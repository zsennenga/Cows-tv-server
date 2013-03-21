<?php
/**
 * Script for returning list of images to display with burnProtect on the cows tv app
 * 
 * @author Zachary Ennenga
 */
if (!isset($_GET['callback'])) exit(0);

$out = array();
$handle = opendir("/var/www/cows/images/");
//Get all images in directory, return file names
while (false !== ($entry = readdir($handle))) {
	if ($entry != "." && $entry != "..") {
		array_push($out,$entry);
	}
}
closedir($handle);
$json = json_encode($out);

echo $_GET['callback'] . "($json);";
?>