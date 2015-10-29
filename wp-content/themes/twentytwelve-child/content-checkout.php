<?php
/**
 * Checkout content
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
?>
	
	<header class='entry-header'><h2>CHECKOUT</h2></header>
	<hr class='small-line' />

	<div class="entry-content<?php if ( is_order_received_page() ) { echo " ac-order_received"; } ?>">
		<?php the_content(); ?>
	</div>
