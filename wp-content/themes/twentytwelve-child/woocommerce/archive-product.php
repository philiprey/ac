<?php
/**
 * Shop page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 * 
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

	<?php do_action( 'woocommerce_archive_description' ); ?>

	<?php if ( have_posts() ) : ?>
				
		<?php
			/**
			 * woocommerce_before_shop_loop hook
			 *
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
		?>
			
		<?php woocommerce_product_subcategories(); ?>
		
		<?php 
			query_posts( 
				array( 
					'post_type' => 'product', 
					'posts_per_page' => -1, 
					'product_cat' => '7inches,10inches,12inches,tape',
					'orderby' => 'date',
					'order' => 'DESC'
				)
			);
		?>
		
		<header class='entry-header'><h2>RECORDS</h2></header>
		<hr class='small-line' />
		<ul class='shop-grid records'>
		
			<?php while ( have_posts() ) : the_post(); ?>
				<?php include( locate_template( 'woocommerce/content-product.php' ) ); ?>
			<?php endwhile; // end of the loop. ?>
			
		</ul>
		
		<?php 
			query_posts( 
				array( 
					'post_type' => 'product', 
					'posts_per_page' => -1, 
					'product_cat' => 'digital,other',
					'orderby' => 'date',
					'order' => 'DESC'
				)
			);
		?>
		
		<header class='entry-header'><h2>DIGITAL</h2></header>
		<hr class='small-line' />
		<ul class='shop-grid digital'>
		
			<?php while ( have_posts() ) : the_post(); ?>
	
				<?php wc_get_template_part( 'content', 'product' ); ?>
	
			<?php endwhile; // end of the loop. ?>
			
		</ul>

		<?php
			/**
			 * woocommerce_after_shop_loop hook
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		?>

	<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

		<?php wc_get_template( 'loop/no-products-found.php' ); ?>

	<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
	
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>
