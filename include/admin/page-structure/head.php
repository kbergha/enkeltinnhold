<!DOCTYPE html>
<html lang="no">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $siteConfig['name']; ?> - admin</title>
    <meta name="description" content="">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="/css/vendor/datetimepicker/bootstrap-datetimepicker.min.css" media="screen">
    <link rel="stylesheet" href="/css/vendor/trumbowyg/ui/trumbowyg.min.css" media="screen">
    <link rel="stylesheet" href="/css/main.css" media="screen">
    <link rel="stylesheet" href="/css/admin.css" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <script src="/js/respond.min.js"></script>
    <![endif]-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <?php
    if(isset($siteConfig['googleWebFont']) && is_array($siteConfig['googleWebFont'])) {
        ?>
        <script src="//ajax.googleapis.com/ajax/libs/webfont/1.5.10/webfont.js"></script>
        <script>
            WebFont.load({
                google: {
                    families: <?php echo json_encode($siteConfig['googleWebFont']); ?>

                }
            });
        </script>
    <?php
    }
    ?>
    <script src="/js/vendor/trumbowyg/trumbowyg.min.js"></script>
    <script src="/js/vendor/moment/moment-with-locales.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/vendor/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/admin.js"></script>
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/admin/index.php"><?php echo $siteConfig['name']; ?> - admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!--<li class="active"><a href="/">Startsiden</a></li>-->
                <!--<li><a href="#">Om</a></li>-->
            </ul>
            <?php
            $login = new \Enkeltinnhold\Login();
            if($login->isLoggedIn()) {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><span>Logget inn som <?php echo $login->getLoggedInUser(); ?>,</span></li>
                    <li><a href="/admin/index.php?logout=true">logg ut</a></li>
                </ul>
            <?php
            }
            ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>