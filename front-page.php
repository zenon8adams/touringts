<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php the_title(); ?></title>
    <link rel="stylesheet" href="<?php echo style_url_( 'front-page-style.css'); ?>">
    <link rel="stylesheet" href="<?php echo style_url_( 'loader.css'); ?>">
    <link rel="stylesheet" href="<?php echo style_url_( 'bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo absurl_( 'style.css'); ?>">
</head>
<body>
    
    <?php
        include_once absdir_( "templates/loader.php");
    ?>
    
    <div class="content-wrapper">
        <div class="logo-wrapper">
            <img class="logo" src="<?php image_url_('Logo.png'); ?>" alt="">
        </div>
        <div class="explore-box">
            <h1 class="upper-text">
                Tell Us About Your Exploration
            </h1>
            <div class="explore" data-explore-button-url="<?php goto_( 'material-selection'); ?>">
                <span>Explore <b style="text-align: right">&rightarrow;</b></span>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo js_url_( 'bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo js_url_( 'loader.js'); ?>"></script>
    <script type="text/javascript">
        let exploreButton = document.querySelector( '.explore');
        
        exploreButton.onclick = function( event) {
            document.querySelector( '.loader').style.display = "block";
            let next_page = exploreButton.getAttribute( 'data-explore-button-url');
            
            window.location.href = next_page;
        }
    </script>    
    <?php
        get_footer();
    ?>