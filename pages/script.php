<?php

    $scripts = [
      "loader.js" => 'js_url_',
      "jquery.min.js" => 'js_url_',
      "popper.js" => 'https://unpkg.com/@popperjs/core@2',
      "tippy.js" => 'https://unpkg.com/tippy.js@6',
      "bootstrap.min.js" => 'js_url_',
      "aos.js" => 'js_url_',
      "fabric.js" => 'js_url_'
    ];
    
    load_resource( "scripts", $scripts);
?>

<script>
    // AOS ANIMATION
    AOS.init({
      disable: 'mobile',
      duration: 800,
      anchorPlacement: 'center-bottom'
    });
</script>