<?php
/**
 * Conditions table.
 *
 * Display table with all the user configured shipping conditions.
 *
 * @author		Jeroen Sormani
 * @package 	WooCommerce Advanced Shipping
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$zones = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'shipping_zone', 'post_status' => array( 'draft', 'publish' ) ) );
?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<?php _e( 'Shipping Zones', 'woocommerce-advanced-shipping' ); ?>:<br />
<!-- 		<small>Read more</small> -->
	</th>
	<td class="forminp" id='shipping_zones_table'>

		<table class='wp-list-table was-table widefat'>
			<thead>
				<tr>
					<th style='padding-left: 10px;'><?php _e( 'Title', 'woocommerce-advanced-shipping' ); ?></th>
				</tr>
			</thead>

			<tbody><?php

				$i = 0;
				foreach ( $zones as $zone ) :

					$alt = ( $i++ ) % 2 == 0 ? 'alternate' : '';
					?><tr class='<?php echo $alt; ?>'>
						<td>
							<strong>
								<a href='<?php echo get_edit_post_link( $zone->ID ); ?>' class='row-title' title='<?php _e( 'Edit Method', 'woocommerce-advanced-shipping' ); ?>'>
									<?php echo $zone->post_title; echo empty( $zone->post_title) ? __( 'Untitled', 'woocommerce-advanced-shipping' ) : null; ?>
								</a>
							</strong>
							<div class='row-actions'>
								<span class='edit'>
									<a href='<?php echo get_edit_post_link( $zone->ID ); ?>' title='<?php _e( 'Edit Method', 'woocommerce-advanced-shipping' ); ?>'>
										<?php _e( 'Edit', 'woocommerce-advanced-shipping' ); ?>
									</a>
									 |
								</span>
								<span class='trash'>
									<a href='<?php echo get_delete_post_link( $zone->ID ); ?>' title='<?php _e( 'Delete Method', 'woocommerce-advanced-shipping' ); ?>'>
										<?php _e( 'Delete', 'woocommerce-advanced-shipping' ); ?>
									</a>
								</span>
							</div>
						</td>

					</tr><?php

				endforeach;

				if ( empty( $zones ) ) :

					?><tr>
						<td colspan='2'><?php _e( 'There are no WooCommerce Advanced Shipping Shipping Zones. Yet...', 'woocommerce-advanced-shipping' ); ?></td>
					</tr><?php
					 endif;

			?></tbody>

			<tfoot>
				<tr>
					<th colspan='4' style='padding-left: 10px;'>
						<a href='<?php echo admin_url( 'post-new.php?post_type=shipping_zone' ); ?>' class='add button'>
							<?php _e( 'Add Shipping zone', 'woocommerce-advanced-shipping' ); ?>
						</a>
					</th>
				</tr>
			</tfoot>
		</table>
	</td>
</tr>