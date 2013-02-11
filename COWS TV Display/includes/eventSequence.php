<?php
/**
 * eventSequence.php
 * 
 * Contains the class eventSequence and associated functions
 */
require_once("cowsRss.php");
require_once("event.php");
/**
 * eventSequence
 * 
 * Contains and manages a sequence of event objects
 * 
 * @author its-zach
 *
 */
class eventSequence	{
	/**
	 * eventList
	 * 
	 * array containing all the events referenced by this sequence
	 * 
	 * @var array
	 */
	private $eventList;
	/**
	 * displayPast
	 * 
	 * boolean, defaulting to false, which specifies whether or not events in the past are to be outputed from toString()
	 * 
	 * @var boolean
	 */
	private $displayPast;
	
	/**
	 * __construct
	 * 
	 * Builds a sequence of events in $eventList
	 * 
	 * @param array $eventArray
	 */
	function __construct($eventArray)	{
		$this->eventList = array();
		foreach ($eventArray as $event)	{
			array_push($this->eventList,new event($event));
		}
		$this->displayPast = false;
	}
	/**
	 * 
	 * setDisplayPast
	 * 
	 * sets the variable DisplayPast
	 * 
	 * @param boolean $bool
	 */
	function setDisplayPast($bool)	{
		$this->displayPast = $bool;
	}
	/**
	 * 
	 * toString
	 * 
	 * Returns a string containing the html to display all the events described by this eventSequence
	 * 
	 * @return string
	 */
	function toString()	{
		$eventArray = $this->eventList;
		$str = "";
		foreach($eventArray as $event)	{
			if (!$event->isPast() || $this->displayPast)	{
				$str .= $event->toString();
			}
		}
		return $str;
	}
}
?>