<?php
/**
 * Shopping cart/checkout process page 
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( is_cart() ): ?>	
					<?php get_template_part( 'content', 'cart' ); ?>
				<?php else: ?>
					<?php get_template_part( 'content', 'checkout' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>

		</div>
	</div>

<?php get_footer(); ?>