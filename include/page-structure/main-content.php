<div class="container main">
<?php
$page = new \Enkeltinnhold\Page(); // @todo: få brukt samme objekt som i startup? singelton?
$page->resolvePage();
echo $page->getPageData();
?>
</div>