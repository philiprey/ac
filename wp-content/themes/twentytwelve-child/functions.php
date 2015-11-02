<?php
function get_childTheme_url() {
    return dirname( get_bloginfo( 'stylesheet_url' ) );
}

function favicon_link() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="' . get_childTheme_url() . '/img/favicon.ico" />' . "\n";
}
add_action( 'wp_head', 'favicon_link' );

if ( !is_admin() ) {
	wp_enqueue_style( 'sc-player', get_bloginfo( 'stylesheet_directory' ) . '/css/sc-player.css' );
	
    wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array( 'jquery' ), '1.11.4' );
 	
 	wp_enqueue_style( 'style-name', get_bloginfo( 'stylesheet_directory' ) . '/css/jquery.fancybox-1.3.4.css' );
 	wp_enqueue_script( 'script-name', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.fancybox-1.3.4.pack.js', array(), '1.0.0', true );

 	wp_register_script( 'jquery-validate',  get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.validate.js', array( 'jquery' ), '1.0' );
 	wp_enqueue_script( 'jquery-validate' );
 	
 	wp_register_script( 'infinite-scroll',  get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.infinitescroll.js', array( 'jquery' ), '1.0' );
 	wp_enqueue_script( 'infinite-scroll' );
 	
 	wp_register_script( 'souncloud-api',  get_bloginfo( 'stylesheet_directory' ) . '/js/soundcloud.player.api.js', array( 'jquery' ), '1.0' );
 	wp_enqueue_script( 'souncloud-api' );
 	
 	wp_register_script( 'souncloud-player',  get_bloginfo( 'stylesheet_directory' ) . '/js/sc-player.js', array( 'jquery' ), '1.0' );
 	wp_enqueue_script( 'souncloud-player' );

    wp_register_script( 'custom-script', get_bloginfo( 'stylesheet_directory' ) . '/js/ac.js', array( 'jquery' ), '1.0' );
    wp_enqueue_script( 'custom-script' );
}

/* remove woocommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/* remove showing results functionality site-wide */
function woocommerce_result_count() { return; }

/* custom image sizes */
add_image_size( 'news-thumb', 560, 9999 );
add_image_size( 'single-news-thumb', 704, 9999 );
add_image_size( 'last-releases-thumb', 174, 9999 );
add_image_size( 'releases-grid-thumb', 374, 9999 );
add_image_size( 'release-cover-thumb', 176, 9999 );
add_image_size( 'shop-thumb', 306, 9999 );
add_image_size( 'download-thumb', 250, 9999 );

/* add 'Artist' & 'Shops' post types */
add_action( 'init', 'create_posttype' );
function create_posttype() {
	register_post_type( 'ac_artist',
		array(
			'labels' => array(
				'name' => __( 'AC Artists' ),
				'singular_name' => __( 'AC Artist' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'ac-artist')
		)
	);
	register_post_type( 'ac_shop',
		array(
			'labels' => array(
				'name' => __( 'AC Shops' ),
				'singular_name' => __( 'AC Shop' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'ac-shop')
		)
	);
	register_post_type( 'ac_article',
		array(
			'labels' => array(
				'name' => __( 'AC Articles' ),
				'singular_name' => __( 'AC Article' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'ac-article')
		)
	);
	register_post_type( 'ac_friend',
		array(
			'labels' => array(
				'name' => __( 'AC Friends' ),
				'singular_name' => __( 'AC Friend' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'ac-friend')
		)
	);
	register_post_type( 'ac_show',
		array(
			'labels' => array(
				'name' => __( 'AC Shows' ),
				'singular_name' => __( 'AC Show' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'ac-show')
		)
	);
}

/* infinite scroll */
function custom_infinite_scroll_js() {
	if( ! is_singular() ) { ?>
	<script>
	var infinite_scroll = {
		loading: {
			img: "<?php echo get_bloginfo( 'stylesheet_directory' ); ?>/img/post-loader.gif",
			msgText: "",
			finishedMsg: ""
		},
		"nextSelector": "#nav-below .nav-previous a",
		"navSelector": "#nav-below",
		"itemSelector": "article",
		"contentSelector": "#news,#journal"
	};
	jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );
	</script>
	<?php
	}
}
add_action( 'wp_footer', 'custom_infinite_scroll_js', 100 );

/* get product ID by SKU */
function get_product_id_by_sku( $sku ) {
	global $wpdb;
	$product_id = $wpdb->get_var( 
		$wpdb->prepare( "SELECT posts.ID FROM $wpdb->posts AS posts
			LEFT JOIN $wpdb->postmeta AS postmeta ON ( posts.ID = postmeta.post_id )
			WHERE posts.post_type IN ( 'product', 'product_variation' )
			AND postmeta.meta_key = '_sku' AND postmeta.meta_value = '%s'
			LIMIT 1", $sku
		)
	);
	
	if ( $product_id ) {
		return $product_id;
	}
	return null;
}

/* get product SKU by ID */
function get_product_sku_by_id( $id ) {
	global $product;
  	if ( empty( $id ) ) {
		$sku = $product->get_sku();
	} else {
		$product = wc_get_product( $id );
	  	$sku = $product->get_sku();		
	}
	return $sku;
}

/* get release attribute value */
function get_release_attribute_value( $post_id, $attribute ) {
	$result = "";
	
	$terms = get_the_terms( $post_id, 'pa_' . $attribute );
	if ( $terms && ! is_wp_error( $terms ) ) : 					
		$result = $terms[0]->name;
	endif;	
	return $result;
}

/* remove links from post images */
add_filter( 'the_content', 'attachment_image_link_remove_filter' );
function attachment_image_link_remove_filter( $content ) {
	$content = preg_replace( array('{<a(.*?)(wp-att|wp-content\/uploads)[^>]*><img}', '{ wp-image-[0-9]*" /></a>}'), array('<img','" />'), $content );
	return $content;
}

/* get content with formatting */
function get_the_content_with_formatting( $more_link_text = '(more...)', $stripteaser = 0, $more_file = '' ) {
	$content = get_the_content( $more_link_text, $stripteaser, $more_file );
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}

/* clean content */
function clean_html( $content ) {
	$content = preg_replace( "/<p[^>]*>[\s|&nbsp;]*<\/p>/", "", $content );
	return $content;
}

/* format specific contents */
function format_content( $text, $mode ) {
	switch ($mode) {
		case "up":
			return strtoupper( $text );
		case "ucw":
			if ( strlen( $text ) <= 3 ) {
				return strtoupper( $text );
			} else {
				return ucwords( strtolower( $text ) );
			}
	}
}

/* check if URL is properly formatted */
function format_url( $url ) {
	if ( strpos( $url, "http://" ) !== false || strpos( $url, "https://" ) !== false ) {
		return $url;
	} else {
		return "http://" . $url;		
	}
}

/* transform custom shortcodes [name|url] into links */
function process_links( $content ) {
	preg_match_all( "/\[([^\|]+)\|([^\|]+)\]/", $content, $matches, PREG_SET_ORDER );
	foreach ( $matches as $data ) {
		$content = str_replace( $data[0], "<a href='" . $data[2] . "' target='_blank'>" . $data[1] . "</a>", $content );
	}
	return $content;
}

/* add 'Read more' after second paragraph */
function add_read_more( $content ) {
	$p_count = substr_count( $content, "<p>" );
	if ( $p_count > 2 ) {
		$paragraphs = explode( "</p>", str_replace( "<p>", "", $content ) );
		$content = "<p>" . array_shift( $paragraphs ) . "</p><p>" . array_shift( $paragraphs ) . "<span id='before-read-more'>..&nbsp;</span><a href='javascript:void(0)' id='read-more'>READ MORE</a></p>";
		$content .= "<div id='description-more'><p>" . implode( "</p><p>", $paragraphs ) . "</p></div>";
	}
	return $content;
} 

/* check if product is a preorder */
function is_preorder( $product_id ) {
	$published_date = get_field( 'release-date' );
	if ( strtotime( $published_date ) - strtotime( 'now' ) < 0 ) {
		return false;
	} else {
		return true;
	}
}

/* change Shop page title */
add_filter( 'wp_title', 'title_for_shop' );
function title_for_shop( $title ) {
	if ( is_shop() ) {
		return __( 'Shop | ' );
	}
	return $title;
}

/* display all products in shop page */
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return -1;' ), 20 );

/* remove woocommerce's checkout login message */	
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

/* override woocommerce's add to cart js */
wp_dequeue_script( 'wc-add-to-cart' );
wp_enqueue_script( 'wc-add-to-cart', get_bloginfo( 'stylesheet_directory' ). '/js/add-to-cart.js' , array( 'jquery' ), false, true );
