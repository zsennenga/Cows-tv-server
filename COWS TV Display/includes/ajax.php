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

$sequence = eventSequence::createSequenceFromArrayCountBounded($cows->getData(time()),6,false);

$eventList = $sequence->getList();
for ($i = 0; $i < 6; $i++)	{
	$out[$i] = $eventList[$i]->toString();
}
$json = json_encode($out);
echo $_GET['callback'] . "($json);";
?>