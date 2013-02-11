<?php
//Include and initialize parser
require_once('./includes/cowsRss.php');
require_once('./includes/eventSequnce.php');

try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	echo $e->getmessage();
	exit(0);
}
$sequence = new eventSequence($cows->getData());
//Header
require('./includes/header.html');
$sequence->toString();
//footer
require('./includes/footer.html');
echo "</body>\n</html>";
?>