<div class="container main">
<?php
$login = new Enkeltinnhold\Login();
$config = $login->getSiteConfig();
$predisClient = $login->getPredisClient();

if(isset($_GET['logout'])) {
    $login->destroySession();
}

if($login->isLoggedIn() == false) {
    ?>
    <h1>Innlogging:</h1>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Epost:</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Skriv inn epostadresse">
        </div>
        <div class="form-group">
            <label for="password">Passord:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Passord">
        </div>
        <button type="submit" class="btn btn-default">Logg inn</button>
    </form>
    <?php
} else {
    ?>
    <h1>Kontrollpanel</h1>
    <?php

    $page = new \Enkeltinnhold\Page(); // @todo move to a "page manager" ?

    $allPages = $page->getAllPageKeys();
    if(is_array($allPages) && count($allPages)) {
        echo '<h2>Alle vanlige sider:</h2>';
        echo '<ol>';
        foreach($allPages as $pageKey) {
            $individualPage = new \Enkeltinnhold\Page($pageKey);
            if($individualPage->load()) {
                echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$individualPage->title.' ('.$pageKey.')</a></li>';
            } else {
                echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$pageKey.'</a></li>';
            }

        }
        echo '</ol>';
    }

    $allReservedPages = $page->getAllReservedPageKeys();
    if(is_array($allReservedPages) && count($allReservedPages)) {
        echo '<h2>Alle systemsider:</h2>';
        echo '<ol>';
        foreach($allReservedPages as $pageKey) {
            $individualPage = new \Enkeltinnhold\Page($pageKey);
            if($individualPage->load()) {
                echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$individualPage->title.' ('.$pageKey.')</a></li>';
            } else {
                echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$pageKey.'</a></li>';
            }
        }
        echo '</ol>';
    }

    if(isset($_GET['page']) && is_string($_GET['page'])) {

        /*
         * Brygget x.y - tappet xx.yy - settes kaldt +10 dager osv.
        $timestamp = date('c'); //2015-01-31T14:30:22+01:00
        $dateTime = DateTime::createFromFormat(DateTime::ISO8601, $timestamp);
        debug($dateTime->format(DateTime::ISO8601));
        $dti = new DateInterval('P2D'); // Period 2 days, Time, 2 hours. P4Y1M2DT1H2M3S, 4 years, 1 month, 2 days, 1 hour, 2 minutes, 3 seconds.
        $dateTime->add($dti);
        debug($dateTime->format(DateTime::ISO8601));
        //$dateTime->format()
        */

        $editPageKey = $_GET['page'];
        $allPageData = $predisClient->hgetall($page->getMasterKey().':'.$_GET['page']);

        $title = '';
        if(isset($allPageData['title'])) {
            $title = $allPageData['title'];
        }

        $created = '';
        if(isset($allPageData['created'])) {
            $created = $allPageData['created'];
        }

        $createdBy = '';
        if(isset($allPageData['createdBy'])) {
            $createdBy = $allPageData['createdBy'];
        }

        $updated = '';
        if(isset($allPageData['updated'])) {
            $updated = $allPageData['updated'];
        }

        $updatedBy = '';
        if(isset($allPageData['updatedBy'])) {
            $updatedBy = $allPageData['updatedBy'];
        }

        $pageData = '';
        if(isset($allPageData['pageData'])) {
            $pageData = $allPageData['pageData'];
        }

        // @todo: validate and sanitize

        $uniqueID = uniqid("page-");

        ?>
        <form class="form-horizontal page-edit">
            <input type="hidden" name="pageKey" value="<?php echo $editPageKey; ?>">
            <fieldset>
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Tittel</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Tittel" value="<?php echo $title; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="<?php echo $uniqueID; ?>" class="col-sm-2 control-label">Tekst</label>
                    <div class="col-sm-10">
                        <?php
                        echo '<textarea name="pageData" id="'.$uniqueID.'">';
                        echo $pageData;
                        echo '</textarea>';
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Sist oppdatert</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $updated; ?> av <?php echo $updatedBy; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary btn-lg has-spinner" id="fjas">
                            <span class="update">Lagre innhold</span>
                            <span class="glyphicon glyphicon-refresh glyphicon-spin"></span>
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
        <script>
            $(document).ready(function() {
                $('#<?php echo $uniqueID; ?>').trumbowyg({
                    semantic: true,
                    btnsDef: {
                        // Customized dropdown
                        formattingLight: {
                            dropdown: ['h2', 'h3', 'p', 'blockquote'],
                            ico: 'formatting' //
                        }
                    },
                    btns: ['viewHTML', '|', 'formattingLight', '|', 'bold', 'italic', '|', 'link', '|', 'unorderedList', 'orderedList']
                });

                // $('#editor').trumbowyg('html');

            });
        </script>
        <?php
    }
}
?>
</div>