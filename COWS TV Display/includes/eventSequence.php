<?php
require_once("cowsRss.php");
class eventSequence	{
	function __eventSequence($eventArray)	{
		
	}
	function doLoc($str)	{
		$val = explode(" ",$str);
		echo "<div id = 'loc'>".str_replace("_"," ",$val[0]) . ", Room " . $val[1]."</div>\n";
		return;
	}
	function getDate($str)	{
		$date = explode(" ",$str);
		return $date[0];
	}
	function formatTime($str)	{
		$date = $this->getDate($str);
		$str = str_replace($date,"",$str);
		$str = str_replace(":00 "," ",$str);
		return $str;
	}
	function isPast($str)	{
		//List events that are at most 3 hours lapsed
		return strtotime($str)-10800 < time();
	}
}
?>