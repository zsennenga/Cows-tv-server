<?php
//Include and initialize parser
require_once('simplepie/autoloader.php');

class cowsRss	{
	var $feed;
	//Construct feed object for the cows site. Default to front tv.
	//Appears to work with both atom and rss feeds, but more testing is required.
	//ICS links will NOT work.
	function cowsRss($feedUrl='http://cows.ucdavis.edu/ITS/event/rss?display=Front-TV')	{
		$this->feed = new SimplePie();
		$this->feed->set_feed_url($feedUrl);
		//Our content involves html so we can't strip it
		$this->feed->strip_htmltags(false);
		$ec = $this->feed->init();
		if (!$ec)	{
			throw new Exception($this->feed->error());
		}
		else	{
			$this->feed->handle_content_type();
		}
	}
	//Return Raw feed object
	function getRaw()	{
		return $this->feed;
	}
	//Handles annoying double/triple encoded &nbsp and the like
	//Also strips tags
	function htmlDecode($str)	{
		$str = htmlspecialchars_decode($str);
		$str = htmlspecialchars_decode($str);
		$str = strip_tags($str);
		$str = str_replace('&nbsp;', '', $str);
		return $str;
	}
	function getData()	{
		$items = $this->feed->get_items();
		$i = -1;
		$out = array();
		foreach($items as $item)	{
			$tok = strtok($item->get_content(), "\n");
			$i++;
			$out[$i] = array();
			//Grab title/description first. Purges &nbsp;s and html tags from the description where they tend to crop up.
			$out[$i]['Title'] = array();
			$out[$i]['Description'] = array();
			array_push($out[$i]['Title'],$item->get_title());
			array_push($out[$i]['Description'],$this->htmlDecode(strip_tags($item->get_description(true))));
			while($tok !== false)	{
				$tokenArray = explode(": ",$tok);
				//$tokenArray[0] is the field name, $tokenArray[1] is the value
				//Handle the case where there is no value
				if (!isset($tokenArray[1]))	{
					$tokenArray[0] = str_replace(':','',$tokenArray[0]);
					$tokenArray[1] = '';
				}
				//set array key to value. Make each one an array in case there are multiple values with the same Descriptor. This happens a lot with "Description"
				if(!isset($out[$i][$tokenArray[0]])) {
					$out[$i][$tokenArray[0]] = array();
				}
				array_push($out[$i][$tokenArray[0]], $tokenArray[1]);	
				$tok = strtok("\n");
			}
		}
		return $out;
	}
	
}
?>