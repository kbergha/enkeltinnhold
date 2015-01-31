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

    $page = new \Enkeltinnhold\Page();

    $allPages = $page->getAllPageKeys();
    if(is_array($allPages) && count($allPages)) {
        echo '<h2>Alle vanlige sider:</h2>';
        echo '<ol>';
        foreach($allPages as $pageKey) {
            echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$pageKey.'</a></li>';
        }
        echo '</ol>';
    }

    $allReservedPages = $page->getAllReservedPageKeys();
    if(is_array($allReservedPages) && count($allReservedPages)) {
        echo '<h2>Alle systemsider:</h2>';
        echo '<ol>';
        foreach($allReservedPages as $pageKey) {
            echo '<li><a href="/admin/index.php?page='.$pageKey.'"> '.$pageKey.'</a></li>';
        }
        echo '</ol>';
    }

    if(isset($_GET['page']) && is_string($_GET['page'])) {
        $allPageData = $predisClient->hgetall($page->getMasterKey().':'.$_GET['page']);

        $uniqueID = uniqid("page-");

        echo '<textarea id="'.$uniqueID.'">';
        echo $allPageData['pageData'];
        echo '</textarea>';
        ?>
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
            });
        </script>
        <?php
    }
}
?>
</div>