<div class="container main">
<?php
$base = new Enkeltinnhold\Base();
$page = $base->getPage();
$config = $base->getSiteConfig();
$predisClient = $base->getPredisClient();

echo '<h1>'.$page->title.'</h1>';
echo htmlspecialchars_decode($page->getPageData());

echo '<p class="text-muted">Sist oppdatert '.DateTime::createFromFormat(DateTime::ISO8601, $page->updated)->format($config['dateFormat']).'</p>';
?>
</div>