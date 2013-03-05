<?php
/**
 * ajax.php
 * 
 * Script for returning 
 * 
 * @author Zachary Ennenga
 */
require_once('cowsRss.php');
require_once('eventSequence.php');

try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	exit(0);
}

$timestamp = time();

$beginOfDay = strtotime("midnight", $timestamp);
$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

$sequence = eventSequence::createSequenceFromArrayTimeBounded($cows->getData(time()),$beginOfDay,$endOfDay);

$eventList = $sequence->getList();
for ($i = 0; $i < 6; $i++)	{
	$out[$i] = $eventList[$i]->toString();
}
if (count($out) >= 1)	{
	$json = json_encode($out);
}
else	{
	$json = json_encode(array("<div class='noevent'>No event scheduled for today</div>"));
}
echo $_GET['callback'] . "($json);";
?>