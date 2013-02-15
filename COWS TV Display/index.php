<?php
/**
 * index.php
 * 
 * Basic Test for the Cows RSS parsing system.
 * 
 * @author Zachary Ennenga
 */
require_once('./includes/cowsRss.php');
require_once('./includes/eventSequence.php');

try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	echo $e->getmessage();
	exit(0);
}

//$sequence = new eventSequence($cows->getData());
$sequence = eventSequence::createSequenceFromArrayCountBounded($cows->getData(),10,true);

//Header
require('./includes/header.html');

//content
echo($sequence->toString());


//footer
require('./includes/footer.html');
?>