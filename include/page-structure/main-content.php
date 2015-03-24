<div class="container main">
    <article>
<?php
$base = new Enkeltinnhold\Base();
$page = $base->getPage();
$config = $base->getSiteConfig();
$predisClient = $base->getPredisClient();

echo '<h1>'.$page->title.'</h1>';

if(mb_strlen($page->digest)) {
    echo '<p class="lead">'.$page->digest.'</p>';
}
echo htmlspecialchars_decode($page->getPageData());

$otherData = json_decode($page->getOtherData(), true);

if(isset($otherData['brewed']) && mb_strlen($otherData['brewed'])) {
    echo '<p>Brygget: '.$otherData['brewed'].'</p>';
}
if(isset($otherData['tapped']) && mb_strlen($otherData['tapped'])) {
    echo '<p>Tapped: '.$otherData['tapped'].'</p>';
}
if(isset($otherData['storage-and-serving'])  && mb_strlen($otherData['storage-and-serving'])) {
    echo '<p>Lagring / servering: '.$otherData['storage-and-serving'].'</p>';
}

?>
    </article>
    <?php
    switch($page->pageKey) {
        case 'page:reserved:index':
            // Show index + list of pages.
            $allPages = $page->getAllPageKeys();
            if(is_array($allPages) && count($allPages)) {
                echo '<section>';
                echo '<hr>';
                foreach($allPages as $pageKey) {
                    $individualPage = new \Enkeltinnhold\Page($pageKey);
                    if($individualPage->load(false)) {
                        $digest = $individualPage->digest;
                        if(mb_strlen($digest)) {
                            $digest = '<p class="lead">'.$digest.'</p>';
                        }
                        echo '<article><a href="'.$individualPage->getURL().'"><h1>'.$individualPage->title.'</h1></a>'.$digest.'</article><hr>';
                    }
                }
                echo '</section>';
            }
            break;

        default:
            // Archive?
            echo '<p class="text-muted">Sist oppdatert '.DateTime::createFromFormat(DateTime::ISO8601, $page->updated)->format($config['dateFormat']).'</p>';
            break;
    }


    ?>
</div>