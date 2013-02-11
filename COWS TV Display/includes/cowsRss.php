<?php
//Include and initialize parser
require_once('simplepie/autoloader.php');
define("CSV",1);
define("Keys",2);
class cowsRss	{
	var $feed;
	//Csonstruct feed object for the cows site. Default to front tv.
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
	//Returns the data in a variety of types.
	//Currently, there are: 
	//	CSV, returns data in the format $out[$itemnumber][$int] = "EventDescriptor,Value"
	//	Keys, returns data in the format $out[$itemnumber]['EventDescriptor'] = "Value"
	//Grabs data from the content section, title, and description of each item.
	//The first two elements added are title and description. Description attempts to strip html .
	//Type defaults to CSV. keepBlank defaults to true and keeps descriptors with null/blank data fields. includePaste defaults to false and keeps/removes past events
	//The function returns false if an invalid data type is specified, and returns an empty array if no items are found.
	function getData($type=CSV,$keepBlank=true,$includePast=false)	{
		$items = $this->feed->get_items();
		$i = -1;
		$out = array();
		foreach($items as $item)	{
			$tok = strtok($item->get_content(), "\n");
			$i++;
			$out[$i] = array();
			//Grab title/description first. Purges &nbsp;s and html tags from the description where they tend to crop up.
			if ($type == 1)	{
				array_push($out[$i], "Title,".$item->get_title());
				array_push($out[$i], "Description,".$this->htmlDecode($item->get_description(true)));
			}
			else if ($type == 2)	{
				$out[$i]['Title']	 = $item->get_title();
				$out[$i]['Description'] = $this->htmlDecode(strip_tags($item->get_description(true)));
			}
			else	{
				return false;
			}
			while($tok !== false)	{
				$tokenArray = explode(": ",$tok);
				//$tokenArray[0] is the field name, $tokenArray[1] is the value
				//Handle the case where there is no value
				if (($keepBlank && !isset($tokenArray[1])) || isset($tokenArray[1]))	{
					if (!isset($tokenArray[1]))	{
						$tokenArray[0] = str_replace(':','',$tokenArray[0]);
						$tokenArray[1] = '';
					}
					//Remove past events if they are to be removed.
					if (!$includePast && $tokenArray[0] == "Ends")	{
						if($this->isPast($tokenArray[1]))	{
							unset($out[$i]);
							//Since you're only deleting the highest value, you just need to move i back. Hopefully. Test this.
							$i--;
							break;
						}
					}
					if ($type == 1)	{
						//add CSV to array
						array_push($out[$i], "$tokenArray[0],$tokenArray[1]");
					}
					else if ($type == 2)	{
						//set array key to value. Make each one an array in case there are multiple values with the same Descriptor. This happens a lot with "Description"
						if(!isset($out[$i][$tokenArray[0]])) {
							$out[$i][$tokenArray[0]] = array();
						}
						array_push($out[$i][$tokenArray[0]], $tokenArray[1]);
					}	
				}
				$tok = strtok("\n");
			}
		}
		return $out;
	}
	
}
?>