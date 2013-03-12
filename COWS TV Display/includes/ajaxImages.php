<?php
/**
 * Script for returning 
 * 
 * @author Zachary Ennenga
 */
if (!isset($_GET['callback'])) exit(0);

$out = array();
$handle = opendir("/var/www/cows/images");

while (false !== ($entry = readdir($handle))) {
	array_push($out,$entry);
}
$json = json_encode($out);

echo $_GET['callback'] . "($json);";
?>