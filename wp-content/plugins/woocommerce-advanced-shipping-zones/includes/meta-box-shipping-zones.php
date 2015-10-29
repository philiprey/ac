<?php
/**
 * Shipping zones meta box.
 *
 * Shipping zones meta box to select the zone.
 *
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Shipping Zones
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$countries 	= WC()->countries->get_allowed_countries();
$states 	= WC()->countries->states;

$selected_countries = (array) get_post_meta( $post->ID, '_countries', true );
$selected_states	= (array) get_post_meta( $post->ID, '_states', true );
$zipcode_value		= get_post_meta( $post->ID, '_zipcodes', true );

wp_nonce_field( 'shipping_zones_meta_box', 'shipping_zones_meta_box_nonce' );
?>

<div class='zone_desc'>

		<?php _e( 'This shipping zone can be used to select as a value while using the \'Shipping Zone\' condition.<br/> If a user matches any of the values the condition will match. ', 'woocommerce-advanced-shipping' ); ?>

		<?php _e( '', 'woocommerce-advanced-shipping' ); ?>

</div>

<!-- Countries -->
<p class='option-group'>

	<span class='label'>
		<label for='countries'><?php _e( 'Countries', 'woocommerce-advanced-shipping' ); ?></label>
		<span class='description'>
			<img class='was_tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='24' width='24' />
			<span class='was_desc'><?php _e( 'Select one or more countries. If user matches any selected countries, the condition will match.', 'woocommerce-advanced-shipping' ); ?></span>
		</span>
	</span>

	<select name='countries[]' multiple='multiple' id='countries' class='chosen'><?php

		foreach ( $countries as $key => $country ) :
			?><option <?php selected( in_array( $key, $selected_countries ) ); ?> value='<?php echo $key; ?>'><?php echo $country; ?></option><?php
		endforeach;

	?></select>

</p>

<!-- States -->
<p class='option-group'>

	<span class='label'>
		<label for='states'><?php _e( 'States', 'woocommerce-advanced-shipping' ); ?></label>
		<span class='description'>
			<img class='was_tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='24' width='24' />
			<span class='was_desc'><?php _e( 'Select one or more states. If user matches any selected states, the condition will match.', 'woocommerce-advanced-shipping' ); ?></span>
		</span>

	</span>

	<select name='states[]' id='states' multiple='multiple' class='chosen'><?php

		foreach ( $states as $country => $states ) :

			if ( empty( $states ) ) continue; // Don't show country if it has no states
			if ( ! array_key_exists( $country, $countries ) ) continue; // Skip unallowed countries

			?><optgroup label='<?php echo $countries[ $country ]; ?>'></option><?php

			foreach ( $states as $state_key => $state ) :
				?><option <?php selected( in_array( $country . '_' . $state_key, $selected_states ) ); ?> value='<?php echo $country . '_' . $state_key; ?>'>
					<?php echo $state; ?>
				</option><?php
			endforeach;

			?><option value='<?php echo $key; ?>'><?php echo $country; ?></option><?php

		endforeach;

		foreach ( $countries as $key => $country ) :
			?><option value='<?php echo $key; ?>'><?php echo $country; ?></option><?php
		endforeach;

	?></select>

</p>


<!-- Zipcodes -->
<p class='option-group'>

	<span class='label'>
		<label for='zipcodes'><?php _e( 'Zipcodes', 'woocommerce-advanced-shipping' ); ?></label>
		<span class='description'>
			<img class='was_tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='24' width='24' />
			<span class='was_desc'><?php _e( 'Separate zipcodes by comma (,) or a new line. <br/>Use ranges by separating two values by a dash (-).', 'woocommerce-advanced-shipping' ); ?></span>
		</span>
	</span>

	<textarea name='zipcodes' class='' placeholder='<?php _e( 'Zipcodes', 'woocommerce-advanced-shipping' ); ?>'><?php echo $zipcode_value; ?></textarea>

</p>