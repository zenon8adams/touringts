<?php
    
    $Routes = Array(
        "contact" =>  absdir_( 'pages/contact.php'),
        "map-customization" => absdir_( 'pages/map-customize.php')
    );
        
    global $post;
    $slug = $post->post_name;
    
    $selection = $Routes[ $slug];
    
    get_header();
?>
 
<script type="text/javascript">   
    window.onload = function()
    {
        setTimeout( () => {
            $( '.loader').hide();
            document.body.style.overflow = 'scroll';
        }, 500);
    }
</script>

<?php

    if( $selection != null)
	{
		require $selection;
	}
	else
	{
		if ( have_posts() ) {
			while ( have_posts() ) {

				the_post();

// 				the_title();

				the_content();
			}
		}
	}
?>
    
<?php
    
    get_footer();
    close_tags();
?>