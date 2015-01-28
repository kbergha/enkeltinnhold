<?php

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enkelt innhold</title>
    <meta name="description" content="">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/main.css" media="screen">
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
</head>
<body>
