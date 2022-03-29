<?php
    
    $loaded_js = false;
    
    
    add_action( 'after_setup_theme', 'setup_woocommerce_support' );

    function setup_woocommerce_support()
    {
      add_theme_support('woocommerce');
    }
    
    // Same handler function...
    add_action( 'wp_ajax_ajax_checkout', 'ajax_checkout' );
    add_action( 'wp_ajax_nopriv_ajax_checkout', 'ajax_checkout' );
    
    add_action( 'wp_ajax_ajax_states', 'ajax_states' );
    add_action( 'wp_ajax_nopriv_ajax_states', 'ajax_states' );
    
    /*function add_to_cart() {
		ob_start();


		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['productID'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = 0;
		$variation         = array();

		if ( $product && 'variable' === $product->get_type() ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
			$variation    = $product->get_variation_attributes();
			$pa_size      = $_POST[ 'size'];
// 			$pa_color     = $_POST[ ''];
            
			
			echo "Variation: ";
			var_dump( $variation);
		}

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			self::get_refreshed_fragments();

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
	}
    */
    
    function filter_variations( $variation)
    {
        $attrs = $variation->get_attributes();
        return $attrs == $_POST[ 'attributes'];
    }
    
    function ajax_checkout()
    {
        
        $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['productID'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['qty'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['qty'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = 0;
		$attributes         = array();
        
        if ( $product && 'variable' === $product->get_type() ) {

            $variations = $product->get_available_variations( 'objects');
            $variation_match = array_pop( array_filter( $variations, 'filter_variations'));
			$variation_id = $variation_match->get_variation_id();
            
            $attributes = array_map( function( $v){ return 'attribute_' . $v; }, array_keys( $_POST[ 'attributes']));
        }
        
        if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes ) && 'publish' === $product_status )
        {
            echo "Success";
        }        
        
        // $cart_item = WC()->cart->get_cart_item( $cart_item_key);
		
        // 		var_dump( $cart_item_key);

        
    }
    
    function ajax_states()
    {
        $countryID = $_POST[ 'countryID'];
        $countries_obj   = new WC_Countries();
        $states = $countries_obj->get_states( $countryID );
        
        if( !empty( $state))
            print_r( json_encode( $states));
    }
    
    add_filter( 'woocommerce_add_cart_item_data', 'wdm_add_item_data', 1, 10);
    function wdm_add_item_data( $cart_item_data, $product_id) 
    {
        global $woocommerce;
        $new_value = array();
        $new_value[ '_design-front'] = $_POST[ 'Front'];
        $new_value[ '_design-back'] = $_POST[ 'Back'];
        
        if(empty($cart_item_data))
            return $new_value;
        else
            return array_merge( $cart_item_data, $new_value);
    }
    
    add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );
    function wdm_get_cart_items_from_session( $item, $values, $key) {
    
        if ( array_key_exists( '_design-front', $values) || array_key_exists( '_design-back', $values)) {
            $item[ '_design-front'] = $values[ '_design-front'];
            $item[ '_design-back']  = $values[ '_design-back'];
        }
    
        return $item;
    }
    
    add_filter( 'woocommerce_cart_item_thumbnail', 'wdm_usr_custom_session', 1, 3);
    function wdm_usr_custom_session( $product_image, $cart_item, $cart_item_key ) {
    
        return  '
                    <span>
                        <img src="' . $cart_item[ '_design-front'] . '">
                    </span>
                    <span>
                        <img src="' . $cart_item[ '_design-back'] . '">
                    </span>
                ';
    }
    
    add_action('woocommerce_add_order_item_meta', 'wdm_add_values_to_order_item_meta', 1, 2);
    
    function wdm_add_values_to_order_item_meta($item_id, $values) {
        // global $woocommerce,$wpdb;
    
        wc_add_order_item_meta( $item_id, '_design_front', $values['_design-front']);
        wc_add_order_item_meta( $item_id, '_design_back', $values['_design-back']);
        // wc_add_order_item_meta($item_id,'customer_image',$values['_design']['another_example_field']);
        // wc_add_order_item_meta($item_id,'_hidden_field',$values['_design']['hidden_info']);
    
    }
    
    add_action('woocommerce_before_cart_item_quantity_zero','wdm_remove_user_custom_data_options_from_cart', 1, 3);
    function wdm_remove_user_custom_data_options_from_cart( $cart_item_key)
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
	
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
	
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
                // "bootstrap.min.css" => [ "shop", "cart", "checkout"],
                // "bootstrap.min.js" => [ "map-customization"],
                // "fabric.js" => [ "*", "map-customization"]
                // "jquery.min.js" => [ "checkout"]
            ];
            
            $pages = $exclude_in_page[ $resource];
            $page_slug = get_post_field( 'post_name');
            if( $pages != null)
            {
                $present = in_array( $page_slug, $pages);
                if( $pages[ 0] == "*" && !$present)
                    continue;
                if( $present)
                    continue;
            }
            
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
        
        $page = basename(get_permalink());
        if( $page == "map-customization")
        {
            wp_enqueue_script( 'bootstrapjs', js_url_( '/bootstrap.min.js'), array(), "1.0");
            wp_enqueue_script( 'mapcustomizejs', js_url_( '/map-customize.js'), array( 'bootstrapjs'), "1.0");
            wp_localize_script( 'mapcustomizejs', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
        }
        else if( $page == "checkout")
        {
            // wp_enqueue_script( 'jqueryjs', js_url_( 'jquery.min.js'), array(), '1.0' );
            wp_register_script( 'checkoutjs', js_url_( '/checkout.js'), array( 'jquery'), "1.0");
            wp_enqueue_script( 'checkoutjs');
            wp_localize_script( 'checkoutjs', 'ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php'),
                'states' => include WC()->plugin_path() . '/i18n/states.php'));
        }
    }
    
    add_action( 'wp_enqueue_scripts', 'load_ajax_scripts');
    
    function add_product_to_cart( $product, $qty = 1)
    {
		$product_id = $product->get_id();
		return WC()->cart->add_to_cart( $product_id, $qty);
    }
    
    add_filter( 'woocommerce_add_cart_item', 'set_cart_item_size', 20, 2 );
    function set_cart_item_size( $cart_item_data, $cart_item_key ) {
        
        if ( isset( $cart_item_data['variation']['attributes'][ 'pa_size']) )
            $cart_item_data['variation']['attributes'][ 'pa_size'] = $_POST[ 'size'];

        return $cart_item_data;
    }
    
    
    
    function getCartItemQuantity()
    {
        $items = WC()->cart->get_cart();
        $qty = 0;
        foreach($items as $item => $values) 
            $qty += $values['quantity']; 
        return $qty;
    }
    
    // add_action( 'woocommerce_checkout_init', 'woocommerce_countries_states', 10, 1 );
    
    function woocommerce_countries_states( $checkout ) {
        global $woocommerce;
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');
        $default_country = $countries_obj->get_base_country();
        $default_county_states = $countries_obj->get_states( $default_country );
    
    
        $checkout->checkout_fields['account']['billing_country'] = array(
            'type'              => 'select',
            'label'             => __( 'Default Contries', 'woocommerce' ),
            'required'          => true,
            'options'    => $countries
        );
    
        $checkout->checkout_fields['account']['billing_country'] = array(
            'type'              => 'select',
            'label'             => __( 'Default Contries', 'woocommerce' ),
            'required'          => true,
             'options'    => $default_county_states
        );
    }
    
    add_action( 'wp_loaded', 'empty_cart_content');
    
    function empty_cart_content()
    {
        if( isset( $_REQUEST[ 'empty_cart']))
            WC()->cart->empty_cart();
        unset( $_REQUEST[ 'empty_cart']);
        return;
    }
    
    /*function save_image( $base64_img, $title ) 
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
    }*/
?>