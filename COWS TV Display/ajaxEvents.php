<?php
/**
 * ajaxEvents.php
 * 
 * Script for passing event information to the tv app via javascript
 * 
 * @author Zachary Ennenga
 */
require_once('includes/cowsRss.php');
require_once('includes/eventSequence.php');
//Guarentee callback is set to avoid weird formatting/variable errors
if(!isset($_GET['callback'])) exit(0);
//Get Feed
try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	exit(0);
}
//Generate eventSequence
$sequence = eventSequence::createSequenceFromArrayTimeBounded($cows->getData(time()),strtotime("midnight", time()),strtotime("midnight tomorrow", time()));
//Get the raw list
$eventList = $sequence->getList();
//Put each event string in an array
$index = 0;
if (count($eventList) >= 1)	{
	for ($i = 0; $i < count($eventList); $i++)	{
		if (!$eventList[$i]->isPast())  {
			$out[$i] = $eventList[$i]->toString();
			$index++;
		}
	}
	
	if (count($out) == 0)	{
		$json = json_encode(array(0 => "noEvent",1 => "<div class='noevent'>No events remaining for today</div>"));
	}
	else	$json = json_encode($out);
	
}
//Handle no events case
else if (count($eventList) == 0)	{
	$json = json_encode(array(0 => "noEvent",1 => "<div class='noevent'>No events scheduled for today</div>"));
}
echo $_GET['callback'] . "($json);";
?>