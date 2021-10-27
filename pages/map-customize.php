<?php 
    get_header();
?>
<section class="map-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto col-md-10 col-12 mt-lg-5 text-center" data-aos="fade-up">
                <h4>Map Customization</h4>
                <h1 class="mb-5">Here is where you <strong>customize</strong> your map</h1>
            </div>
        </div>
    </div>
    <div class="map-position-wrapper">
        <!--<div id="js-tray" class="tray">
            <div id="js-tray-slide" class="tray__slide"></div>
        </div>-->
        <div class="map-container">
            <ul class="nav nav-tabs" id="dress-nav" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-btn nav-link active" id="front-tab" data-id="0" data-toggle="tab" data-target="#front" type="button" role="tab" aria-controls="front" aria-selected="true">Front</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-btn nav-link" id="back-tab" data-id="1" data-toggle="tab" data-target="#back" type="button" role="tab" aria-controls="back" aria-selected="false">Back</button>
              </li>
              <li class="nav-item dress-color-toggler" role="presentation">
                <button class="nav-link active" id="dress-color-toggler-tab" type="button" role="tab" aria-controls="dress-color-toggler" aria-selected="false">White</button>
              </li>
            </ul>
            <div class="tab-content" id="map-customize">
              <div class="tab-pane fade show active" id="front" role="tabpanel" aria-labelledby="front-tab">
                  <canvas id="dress-front"></canvas>
              </div>
              <div class="tab-pane fade" id="back" role="tabpanel" aria-labelledby="back-tab">
                <canvas id="dress-back"></canvas>
              </div>
            </div>
        </div>
    </div>
    
    <svg class="cart-item-count" data-cart-qty="<?php echo getCartItemQuantity(); ?>" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="73" height="65" viewBox="214 218 66 60" xml:space="preserve">
        <desc>Created with Fabric.js 4.6.0</desc>
        <defs>
        </defs>
        <g transform="matrix(0.2 0 0 0.2 250 250.42)" id="3EmGgm7_jwcQ6I9TC3uFe"  >
        <path class="cart-item-background" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(139,9,9); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke"  transform=" translate(-149.5, -129.44)" d="M 0 51.9392 L 62.388 0 L 299 0 L 299 258.872 L 0 258.872 L 0.000075479 126.138 L 0 51.9392 Z" stroke-linecap="round" />
        </g>
        <g transform="matrix(0.2 0 0 0.2 252.07 250.42)" id="WOa3Z6Zu7Y8m99Dh4tV_Z"  >
        <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke"  transform=" translate(-159.71, -131.58)" d="M 189.69 138.171 C 194.637 138.171 198.99 135.467 201.232 131.377 L 224.844 88.5728 C 227.284 84.2198 224.119 78.8115 219.106 78.8115 L 121.493 78.8115 L 115.293 65.6206 L 93.7263 65.6206 L 93.7263 78.8115 L 106.917 78.8115 L 130.661 128.871 L 121.757 144.964 C 116.942 153.802 123.274 164.552 133.299 164.552 L 212.445 164.552 L 212.445 151.362 L 133.299 151.362 L 140.554 138.171 L 189.69 138.171 Z M 127.759 92.0025 L 207.894 92.0025 L 189.69 124.98 L 143.39 124.98 L 127.759 92.0025 Z M 133.299 171.148 C 126.044 171.148 120.174 177.084 120.174 184.339 C 120.174 191.594 126.044 197.53 133.299 197.53 C 140.554 197.53 146.49 191.594 146.49 184.339 C 146.49 177.084 140.554 171.148 133.299 171.148 Z M 199.254 171.148 C 191.999 171.148 186.129 177.084 186.129 184.339 C 186.129 191.594 191.999 197.53 199.254 197.53 C 206.509 197.53 212.445 191.594 212.445 184.339 C 212.445 177.084 206.509 171.148 199.254 171.148 Z" stroke-linecap="round" />
        </g>
        <g transform="matrix(0.73 0 0 0.73 229.69 236.73)" style="" id="trv7G8sPx6Q2Hb5yGd-q9"  >
        	<text xml:space="preserve" font-family="Nova Square" font-size="14" font-style="normal" font-weight="normal" line-height="1.16" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1; white-space: pre;"><tspan id="cart-count" x="5" y="7"></tspan></text>
        </g>
    </svg>
    
    <div class="print-button-wrapper">
        <button id="add-to-cart" type="button" class="custom-btn" data-toggle="modal">Add to Cart</button>
    </div>
    
    <div class="modal fade" id="checkoutDialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                  <div class="modal-body">
                    <div class="checkout-image-wrapper">
                        <svg id="checkout-image" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" viewBox="0 0 100 100" y="0" x="0" version="1.1" width="200px" height="200px"><g class="ldl-scale"><path fill="#333" d="M50 5C25.187 5 5 25.187 5 50s20.187 45 45 45 45-20.187 45-45S74.813 5 50 5zm0 80c-19.299 0-35-15.701-35-35s15.701-35 35-35 35 15.701 35 35-15.701 35-35 35z" style="fill:rgb(132, 155, 135);"/>
                            <path fill="#849b87" d="M46.539 70.869l29.208-29.208a5 5 0 1 0-7.071-7.071L43.003 60.262l-12.68-12.68a5.001 5.001 0 0 0-7.071 7.071l16.215 16.215c.977.976 2.256 1.464 3.536 1.464s2.559-.487 3.536-1.463z" style="fill:rgb(132, 155, 135);"/>
                            </g>
                        </svg>
                        <div>You can now proceed to checkout.</div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="close-btn" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="checkout-btn" type="button" class="btn btn-primary">Checkout</button>
                  </div>
            </div>
        </div>
    </div>
    
</section>
<?php
    global $loaded_js;
    
    $loaded_js = true;
    require absdir_( 'pages/script.php');
?>

<?php
    get_footer();
?>