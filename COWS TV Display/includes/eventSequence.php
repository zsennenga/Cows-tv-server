<?php
require_once("cowsRss.php");
require_once("event.php");
class eventSequence	{
	private $eventList;
	private $displayPast;
	
	function __construct($eventArray)	{
		$this->eventList = array();
		foreach ($eventArray as $event)	{
			array_push($this->eventList,new event($event));
		}
		$this->displayPast = false;
	}
	
	function setDisplayPast($bool)	{
		$this->displayPast = $bool;
	}
	function formatTime($str)	{
		$date = $this->getDate($str);
		$str = str_replace($date,"",$str);
		$str = str_replace(":00 "," ",$str);
		return $str;
	}
	
	function toString()	{
		$eventArray = $this->eventList;
		foreach($eventArray as $event)	{
			if (!$event->isPast() || $this->displayPast)	{
				$event->toString();
			}
		}
	}
}
?>