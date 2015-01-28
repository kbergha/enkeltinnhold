<div class="container">
<?php
$page = new \Enkeltinnhold\Page\Page(); // @todo: få brukt samme objekt som i startup? singelton?
$page->resolvePage();

echo $page->getPageData();
?>
</div>