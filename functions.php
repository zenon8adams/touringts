<?php
    
    $loaded_js = false;
    $current_product = null;
    
    
    // Same handler function...
    add_action( 'wp_ajax_ajax_executor', 'ajax_executor' );
    add_action( 'wp_ajax_nopriv_ajax_executor', 'ajax_executor' );
    
    function ajax_executor() 
    {
        $productID = $_POST[ 'productID'];
        
        $product = wc_get_product( $productID);
        add_product_to_cart( $product);
       
    	goto_( 'checkout');
    }
    
    add_filter('woocommerce_add_cart_item_data', 'wdm_add_item_data', 1, 10);
    function wdm_add_item_data($cart_item_data, $product_id) 
    {
        global $woocommerce;
        $new_value = array();
        $new_value['_design'] = $_POST['design'];
        
        if(empty($cart_item_data)) {
            return $new_value;
        } else {
            return array_merge($cart_item_data, $new_value);
        }
    }
    
    add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );
    function wdm_get_cart_items_from_session($item,$values,$key) {
    
        if ( array_key_exists( '_design', $values ) ) {
            $item['_design'] = $values['_design'];
        }
    
        return $item;
    }
    
    add_filter( 'woocommerce_cart_item_thumbnail', 'wdm_usr_custom_session', 1, 3);
    
    function wdm_usr_custom_session( $product_image, $cart_item, $cart_item_key ) {
    
        return '<img src="' . $cart_item[ '_design'] . '">';
    }
    
    add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
    function wdm_add_values_to_order_item_meta($item_id, $values) {
        // global $woocommerce,$wpdb;
    
        wc_add_order_item_meta($item_id,'item_details', $values['_design']);
        // wc_add_order_item_meta($item_id,'customer_image',$values['_design']['another_example_field']);
        // wc_add_order_item_meta($item_id,'_hidden_field',$values['_design']['hidden_info']);
    
    }
    
    add_action('woocommerce_before_cart_item_quantity_zero','wdm_remove_user_custom_data_options_from_cart', 1, 3);
    function wdm_remove_user_custom_data_options_from_cart($cart_item_key)
    {
        global $woocommerce;
        // Get cart
        $cart = $woocommerce->cart->get_cart();
        // For each item in cart, if item is upsell of deleted product, delete it
        foreach( $cart as $key => $values)
        {
        if ( $values[ '_design'] == $cart_item_key )
            unset( $woocommerce->cart->cart_contents[ $key ] );
        }
    }
    
    function js_url_( $url)
    {
        return get_template_directory_uri() . "/assets/js/$url";
    }
    
    function style_url_( $url)
    {
        return get_template_directory_uri() . "/assets/css/$url";
    }
    
    function image_url_( $url)
    {
        echo get_template_directory_uri() . "/assets/images/$url";
    }
    
    function absurl_( $url)
    {
        return get_template_directory_uri() . "/$url";
    }
    
    function absdir_( $path)
    {
        return get_template_directory() . "/$path";
    }
    
    function absuri_( $uri)
    {
        return get_template_directory_uri() . "/$uri";
    }
    
    function close_tags()
    {
        echo "</body>\n</html>";
    } 
    
    function goto_( $path)
    {
        echo get_permalink( get_page_by_path(  $path));
    }
	
	function remove_admin_login_header() 
	{
    	remove_action('wp_head', '_admin_bar_bump_cb');
	}
	
	add_action('get_header', 'remove_admin_login_header');
	
	function load_resource( $type, $resources)
	{
	   // wp_enqueue_style( 'bootstrap-css', absuri_( '/assets/css/bootstrap.min.css'), [], "1.0");
	   // wp_enqueue_style( 'aos-css', absuri_( '/assets/css/aos.css'), [], "1.0");
	   // wp_enqueue_style( 'owl-carousel-css', absuri_( '/assets/css/owl.carousel.min.css'), [], "1.0");
	   // wp_enqueue_style( 'owl-theme-css', absuri_( '/assets/css/owl.theme.default.min.css'), [], "1.0");
	   // wp_enqueue_style( 'scale-css', '//unpkg.com/tippy.js@6/animations/scale.css', [], "1.0");
	   // wp_enqueue_style( 'style-css', absuri_( '/style.css'), [], "1.0");
	    
	    
	    foreach( $resources as $resource => $maybe_function)
        {
            $exclude_in_page = [
                "bootstrap.min.css" => [ "shop", "cart", "checkout"],
                "bootstrap.min.js" => [ "map-customization"],
                "fabric.js" => [ "*", "map-customization"]
            ];
            
            $pages = $exclude_in_page[ $resource];
            $page_slug = get_post_field( 'post_name');
            if( $pages != null && in_array( $page_slug, $pages))
                if( $pages[ 0] != "*")
                    continue;

            switch( $type)
            {
                case "stylesheets":
                    echo '<link rel="stylesheet" href="' . ( function_exists( $maybe_function) ? $maybe_function( $resource) : $maybe_function) . '" crossorigin="anonymous">';
                    break;
                case "scripts":
                    echo '<script type="text/javascript" src="' . ( function_exists( $maybe_function) ? $maybe_function( $resource) : $maybe_function) . '"></script>';
                    break;
                // case "images":
                    // echo '<img src="' . ( function_exists( "$maybe_function") ? $maybe_function( $resource[ 0]) : $resource[ 0]) . '" alt="' . $resource[ 1] . '">';
                    // break;
            }
        }
	}
	
	
	
// 	add_action( 'wp_enqueue_scripts', 'load_stylesheets');


    function load_ajax_scripts()
    {
        // wp_register_script( 'jqueryjs', absuri_( '/assets/js/jquery.min.js'), array(), '1.0' );
        // wp_register_script( 'popperjs', '//unpkg.com/@popperjs/core@2', array( 'jqueryjs'), '1.0');
        // wp_enqueue_script( 'tippyjs', '//unpkg.com/tippy.js@6', array( 'popperjs'), '1.0');
        // wp_enqueue_script( 'bootstrapjs', absuri_( '/assets/js/bootstrap.min.js'), array( 'jqueryjs'), '1.0');
        // wp_enqueue_script( 'aosjs', absuri_( '/assets/js/aos.js'), array( 'jqueryjs'), '1.0');
        
        if( basename(get_permalink()) == "map-customization")
        {
            wp_enqueue_script( 'bootstrapjs', js_url_( '/bootstrap.min.js'), array(), "1.0");
            wp_enqueue_script( 'mapcustomizejs', js_url_( '/map-customize.js'), array( 'bootstrapjs'), "1.0");
            wp_localize_script( 'mapcustomizejs', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
        }
    }
    
    add_action( 'wp_enqueue_scripts', 'load_ajax_scripts');
    
    function add_product_to_cart( $product, $qty = 1)
    {
		$product_id = $product->get_id();
		WC()->cart->add_to_cart( $product_id, $qty);
    }
    
    
    
    function getCartItemQuantity()
    {
        $items = WC()->cart->get_cart();
        $qty = 0;
        foreach($items as $item => $values) 
            $qty += $values['quantity']; 
        return $qty;
    }
    
    function save_image( $base64_img, $title ) 
    {
    	// Upload dir.
    	$upload_dir  = wp_upload_dir();
    	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
        
        
    	$img             = str_replace( 'data:image/png;base64,', '', $base64_img );
    	$img             = str_replace( ' ', '+', $img );
    	$decoded         = base64_decode( $img );
    	$filename        = $title . '.png';
    	$file_type       = 'image/png';
    	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
        
        
    	// Save the image in the uploads directory.
    	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
    
    	$attachment = array(
    		'post_mime_type' => $file_type,
    		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
    		'post_content'   => '',
    		'post_status'    => 'inherit',
    		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
    	);
    
    	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
    	
    	require_once( ABSPATH . 'wp-admin/includes/image.php' );
  
        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_dir['path'] . '/' . $hashed_filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        
        return $attach_id;
    }
?>