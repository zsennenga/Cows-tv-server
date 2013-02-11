<?php
//Include and initialize parser
require('./includes/classes.php');
try	{
	$cows = new cowsRss('http://cows.ucdavis.edu/ITS/event/atom?display=Front-TV');
} catch (Exception $e) {
	echo $e->getmessage();
	exit(0);
}
$data = $cows->getData(Keys,false,false);
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
//Stylesheets & includes
echo "<head><title>ITS Cows Calendar</title><link rel=\"stylesheet\" href=\"./includes/style.css\" type=\"text/css\"></head>\n";
echo "<body>\n";
//Header
require('./includes/header.html');
foreach($data as $dat)	{
	//Content
	echo "<div id = 'title'>".$dat['Title']."</div>\n";
	if($dat['Description'] != '') echo "<div id = 'desc'>" . $dat['Description'] . "</div>\n";
	echo "<div id = 'times'>" . $cows->getDate($dat['Starts']) . ", " . $cows->formatTime($dat['Starts']) . ' - ' . $cows->formatTime($dat['Ends']) . "</div>\n";
	$cows->doLoc($dat['Location']);
	echo "<br/>\n";
}
//footer
require('./includes/footer.html');
echo "</body>\n</html>";
?>