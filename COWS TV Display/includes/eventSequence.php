<?php
require_once("cowsRss.php");
class eventSequence	{
	var $eventList;
	var $_displayPast;
	function __eventSequence($eventArray)	{
		$eventList = array();
		foreach ($eventArray as $event)	{
			array_push($eventList,new event($event));
		}
		$displayPast = false;
	}
	function setDisplayPast($bool)	{
		$displayPast = $bool;
	}
	function formatTime($str)	{
		$date = $this->getDate($str);
		$str = str_replace($date,"",$str);
		$str = str_replace(":00 "," ",$str);
		return $str;
	}
	
	function toString()	{
		foreach($eventList as $event)	{
			if (!$event->isPast() || $displayPast)	{
				$event->toString();
			}
		}
	}
}
?>