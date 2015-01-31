<div class="container main">
<?php
$base = new Enkeltinnhold\Base();
$page = $base->getPage();
$config = $base->getSiteConfig();
$predisClient = $base->getPredisClient();

/*
if($page->isResolved()) {

} else {

}
*/

echo $page->getPageData();

/*
$timestamp = date('c'); //2015-01-31T14:30:22+01:00
$dateTime = DateTime::createFromFormat(DateTime::ISO8601, $timestamp);
debug($dateTime->format(DateTime::ISO8601));
$dti = new DateInterval('P2D'); // Period 2 days, Time, 2 hours. P4Y1M2DT1H2M3S, 4 years, 1 month, 2 days, 1 hour, 2 minutes, 3 seconds.
$dateTime->add($dti);
debug($dateTime->format(DateTime::ISO8601));
//$dateTime->format()
*/


//sscan hashkeys 0 match my*
//debug($predisClient->sscan('47brygg:allpages', 0));
//debug($predisClient->sscan('47brygg:allpages', 0, array('match' => 'page:*')));
//debug($predisClient->sscan('47brygg:allpages', 0, array('match' => 'page:reserved:*')));
?>
</div>