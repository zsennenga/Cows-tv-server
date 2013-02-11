<?php
require_once('eventSequence.php');
class event	{
	private $title;
	private $startTime;
	private $endTime;
	private $date;
	private $description;
	private $location;
	function __event($event)	{
		setTitle($event['Title'][0]);
		foreach($event['Description'] as $des)	{
			if ($des != "") {
				setDescription($des);
			}
		}
		setStartTime($event['Starts'][0]);
		setEndTime($event['Ends'][0]);
		setDate($event['Starts'][0]);
		setLocation($event['Location'][0]);
	}
	function isBlank($var)	{
		return ($$var == "");
	}
	function isPast()	{
		return strtotime($this->startTime)-10800 < time();
	}
	
	function setTitle($str)	{
		$this->title = $str;
	}
	function setDescription($str)	{
		$this->description = $str;
	}
	function setStartTime($str)	{
		$date = explode(" ",$str);
		$str = str_replace($date[0],"",$str);
		$str = str_replace(":00 "," ",$str);
		$this->startTime = $str;
	}
	function setEndTime($str)	{
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
		if (!isBlank($this->description))	{
			echo "<div id = 'desc'>" . $this->description . "</div>\n";
		}
		echo "<div id = 'times'>" . $this->date . ", " . $this->startTime . ' - ' . $this->endTime . "</div>\n";
		echo "<div id = 'loc'>".$this->location."</div>\n";
		echo "<br/>\n";
	}
}
?>