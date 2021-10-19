<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Touring T's</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <?php
            
            $styles = array(
                'bootstrap.min.css' => 'style_url_',
                'aos.css' => 'style_url_',
                'owl.carousel.min.css' => 'style_url_',
                'scale.css' => 'https://unpkg.com/tippy.js@6/animations/scale.css',
                'style.css' => 'absurl_'
            );
            
            load_resource( "stylesheets", $styles);
            
			wp_head();
		?>
    </head>
    <body>
        <div class="loader">
            <div class="content">
                <canvas id="loader" width="200" height="200"></canvas>
                <p>Please wait while <strong>resource</strong> is being loaded<span class="dot"></span></p>
            </div>
        </div>

        <nav class="navbar">
            <a class="navbar-logo" href="index.html">
                <img src="<?php image_url_( 'logo.png'); ?>" class="img-fluid mr-0" style="width: 3rem;" alt="working girl">
                <p>Touring T's</p>
            </a>
            <div class="navbar-nav ml-auto">
                <a href="<?php goto_( 'contact') ?>" class="link">Contact</a>
            </div>
        </nav>
        <div id="info-pane">
            <div class="content">
            </div>
        </div>