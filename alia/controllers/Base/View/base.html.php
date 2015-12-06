<head>
    <title>[title]</title>
    [meta]
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo getUrl(); ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo getUrl(); ?>/css/header.css">
    <link rel="stylesheet" type="text/css" href="<?php echo getUrl(); ?>/css/font-awesome.css">
    <link rel="shortcut icon" href="<?php echo getUrl(); ?>/favicon.ico" />
    <script type="text/javascript" src="<?php echo getUrl(); ?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo getUrl(); ?>/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo getUrl(); ?>/js/main.js"></script>
    <script type="text/javascript" src="<?php echo getUrl(); ?>/js/javascript.js"></script>
    <script type="text/javascript" src="<?php echo getUrl(); ?>/js/sliderModule.js"></script>
    <script>
        $(document).ready(function(){
            var altoReal=$("header").height()+$("main").height()+$("footer").height();
            if($(window).height()>altoReal){
                var principal=$("main");
                var alto=principal.height()+$(window).height()-altoReal-23;
                principal.css("min-height",alto);
            }
        });
    </script>
</head>
