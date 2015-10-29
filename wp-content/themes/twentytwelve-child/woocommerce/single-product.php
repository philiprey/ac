<?php
/**
 * Single release page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php do_action( 'woocommerce_before_main_content' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>
			
		<?php endwhile; // end of the loop. ?>

	<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>
