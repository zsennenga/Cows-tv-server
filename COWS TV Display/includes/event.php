<?php
require_once('eventSequence.php');

class event	{
	private $title;
	private $startTime;
	private $endTime;
	private $date;
	private $description;
	private $location;
	
	function __construct($event)	{
		$this->setTitle($event['Title'][0]);
		foreach($event['Description'] as $des)	{
			if ($des != "") {
				$this->setDescription($des);
			}
		}
		$this->setTime($event['Starts'][0],$event['Ends'][0]);
		$this->setDate($event['Starts'][0]);
		$this->setLocation($event['Location'][0]);
	}
	
	function isPast()	{
		return strtotime($this->date . " ". $this->endTime)-10800 < time();
	}
	
	function setTitle($str)	{
		$this->title = $str;
	}
	function setDescription($str)	{
		$this->description = $str;
	}
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
	function setEndTime($str)	{
		$date = explode(" ",$str);
		$str = str_replace($date[0],"",$str);
		$str = str_replace(":00 "," ",$str);
		$this->endTime = $str;
	}
	function setDate($str)	{
		$date = explode(" ",$str);
		$this->date = $date[0];
	}
	
	function setLocation($str)	{
		$val = explode(" ",$str);
		$this->location = str_replace("_"," ",$val[0]) . ", Room " . $val[1];
	}
	
	function toString()	{
		echo "<div id = 'title'>".$this->title."</div>\n";
		echo "<div id = 'desc'>" . $this->description . "</div>\n";
		echo "<div id = 'times'>" . $this->date . ", " . $this->startTime . ' - ' . $this->endTime . "</div>\n";
		echo "<div id = 'loc'>".$this->location."</div>\n";
		echo "<br/>\n";
	}
}
?>