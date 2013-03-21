<?php
/**
 * ajaxEvents.php
 * 
 * Script for passing event information to the tv app via javascript
 * 
 * @author Zachary Ennenga
 */
require_once('cowsRss.php');
require_once('eventSequence.php');
if(!isset($_GET['callback'])) exit(0);
try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	exit(0);
}

$sequence = eventSequence::createSequenceFromArrayTimeBounded($cows->getData(time()),strtotime(time()),strtotime("midnight tomorrow", time()));

$eventList = $sequence->getList();

if (count($eventList) >= 1)	{
	for ($i = 0; $i < count($eventList); $i++)	{
		$out[$i] = $eventList[$i]->toString();
	}
	$json = json_encode($out);
}
else	{
	$json = json_encode(array(0 => "noEvent",1 => "<div class='noevent'>No event scheduled for today</div>"));
}
echo $_GET['callback'] . "($json);";
?>