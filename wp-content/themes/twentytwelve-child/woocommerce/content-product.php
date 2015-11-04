<?php
/**
 * Release content for Shop page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$product_per_line = 3;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array( 'shop-bloc' );

$post_ID = $post->ID;
?>
<li <?php post_class( $classes ); ?> id="shop-item-<?php echo $post_ID; ?>">
	
	<?php 
		$sku = $product->get_sku();
		$external_link = false;
		$is_digital = false;
		$physical_product_ID = '';
		if ( strpos( $sku, 'd' ) ) {
			$is_digital = true;
			$sku = str_replace( 'd', '', $sku );
			$physical_product_ID = get_product_id_by_sku( $sku );
			$link = get_permalink( $physical_product_ID );
		} else {
			$special_link = get_field( 'release-special_link' );
			if ( empty( $special_link ) ) {
				$link = get_permalink( $post_ID );	
			} else {
				$link = $special_link;
				$external_link = true;
			}
		}
		
		$artists = array();
		$data = get_field( 'product-artists' );
		foreach ( $data as $d ) {
			$artists[] = get_the_title( $d->ID );
		}
		
		$title = get_release_attribute_value( $post_ID, 'release-title' );
		$format = get_release_attribute_value( $post_ID, 'release-format' );
		$price = get_post_meta( $post_ID, '_regular_price' );
		$is_preorder = is_preorder( $post->ID );
		$external_buy_link = get_field( 'release-external_buy_link' );
	?>
		
	<!-- image -->
	<header class="entry-header">
		<?php if ( $is_digital && empty( $physical_product_ID ) ) : ?>
			<a href="javascript:void(0)" name="<?php echo empty( $physical_product_ID ) ? $post_ID : $physical_product_ID; ?>"></a>
			<?php echo the_post_thumbnail( 'shop-thumb' ); ?>
		<?php else : ?>
			<a href="<?php echo $link; ?>"<?php if ( $external_link) { echo " target='_blank'"; } ?> name="<?php echo empty( $physical_product_ID ) ? $post_ID : $physical_product_ID; ?>"><?php echo the_post_thumbnail( 'shop-thumb' ); ?></a>
		<?php endif; ?>
	</header>
	
	<!-- details -->
	<div class="entry-content">
		<div class="artist"><?php echo implode( " & ", $artists ); ?></div>
		<div class="title"><?php echo $title; ?></div>
		<div class="info"><span class="ref"><?php echo $sku; ?></span> <?php echo $format; ?></div>
	</div>
	<div class="entry-price">
		<?php
			if ( empty( $external_buy_link ) ) {
				echo number_format( $price[0], 2 ) . "&euro;";
			}
			if ( ! $is_digital && $is_preorder ) {
				echo " - <span class='preorder tootltip' id='" . $sku . "-preorder' title='Out " . date( 'F d, Y', strtotime( get_field( 'release-date' ) ) ) . "'>PREORDER</span>";
				?>
					<script>
						$j( '#<?php echo $sku . "-preorder"; ?>' ).tooltipster();
				       //jQuery(function () {
				            //$j( '#<?php echo $sku . "-preorder"; ?>' ).tooltipster('show');
				        //});
					</script>
				<?php
			}
		?>
	</div>
	<?php
		/* add to cart / buy button */
		if ( empty( $external_buy_link ) ) {		
			do_action( 'woocommerce_after_shop_loop_item' );
		} else {
			include( locate_template( 'external-buy-btn.php' ) );
		}		
	?>

</li>

<?php 
	if ( $woocommerce_loop['loop'] % $product_per_line != 0 ) {
		echo "<li class=\"shop-gap\">&nbsp;</li> \n";
	}
?>
