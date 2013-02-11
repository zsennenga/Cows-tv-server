<?php
/**
 * event.php
 * 
 * File containing the class event and associated functions
 * 
 */
require_once('eventSequence.php');
/**
 * 
 * event
 * 
 * Class that contains event data. Is aggregated by an eventSequence.
 * 
 * @author Zachary Ennenga
 *
 */
class event	{
	/**
	 * Event Title
	 * @var string
	 */
	private $title;
	/**
	 * Event Start Time
	 * @var string
	 */
	private $startTime;
	/**
	 * Event End Time
	 * @var string
	 */
	private $endTime;
	/**
	 * Event Date in the format MM/DD/YY
	 * @var string
	 */
	private $date;
	/**
	 * Event Description
	 * @var string
	 */
	private $description;
	/**
	 * Event location in the format BLDG, Room ####
	 * @var string
	 */
	private $location;
	
	/**
	 * __construct
	 * 
	 * Constructs an event.
	 * 
	 * @param array $event
	 */
	function __construct($event)	{
		$this->setTitle($event['Title'][0]);
		//Sometimes there are two descriptions, one of which is blank.
		foreach($event['Description'] as $des)	{
			if ($des != "") {
				$this->setDescription($des);
			}
		}
		$this->setTime($event['Starts'][0],$event['Ends'][0]);
		$this->setDate($event['Starts'][0]);
		$this->setLocation($event['Location'][0]);
	}
	/**
	 * isPast
	 * 
	 * checks if a event occured in the past before printing. We have 3 hours of leeway.
	 * 
	 * @return boolean
	 */
	function isPast()	{
		return strtotime($this->date . " ". $this->endTime)-10800 < time();
	}
	/**
	 * setTitle
	 * 
	 * Sets the Event's title
	 * 
	 * @param string $str
	 */
	function setTitle($str)	{
		$this->title = $str;
	}
	/**
	 * setDescription
	 * 
	 * Sets the event's description
	 * 
	 * @param string $str
	 */
	function setDescription($str)	{
		$this->description = $str;
	}
	/**
	 * setTime
	 * 
	 * Parses the start/end times and sets them.
	 * 
	 * @param string $start
	 * @param string $end
	 */
	function setTime($start,$end)	{
		$times = array($start,$end);
		$timesFinal = array();
		foreach($times as $str)	{
			$date = explode(" ",$str);
			$str = str_replace($date[0],"",$str);
			$str = str_replace(":00 "," ",$str);
			array_push($timesFinal,$str);
		}
		$this->startTime = $timesFinal[0];
		$this->endTime = $timesFinal[1];
	}
	/**
	 * setDate
	 * 
	 * Parses the date from a start/end time string
	 * 
	 * @param string $str
	 */
	function setDate($str)	{
		$date = explode(" ",$str);
		$this->date = $date[0];
	}
	/**
	 * setLocation
	 * 
	 * Parses out the location of an event from the cows value
	 * 
	 * @param string $str
	 */
	function setLocation($str)	{
		$val = explode(" ",$str);
		$this->location = str_replace("_"," ",$val[0]) . ", Room " . $val[1];
	}
	/**
	 * Composes and returns the html for a single event.
	 * @return string
	 */
	function toString()	{
		$str = "";
		$str .= "<div id = 'title'>".$this->title."</div>\n";
		$str .= "<div id = 'desc'>" . $this->description . "</div>\n";
		$str .= "<div id = 'times'>" . $this->date . ", " . $this->startTime . ' - ' . $this->endTime . "</div>\n";
		$str .= "<div id = 'loc'>".$this->location."</div>\n";
		$str .= "<br/>\n";
		return $str;
	}
}
?>