<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Value field.
 *
 * Display the value field, the type and values depend on the condition.
 *
 * @since 1.0.0
 *
 * @param mixed 	$id				ID of the current condition.
 * @param mixed 	$group			Group the condition belongs to.
 * @param string 	$condition		Condition.
 * @param string 	$current_value	Current condition value.
 */
function was_condition_values( $id, $group = 0, $condition = 'subtotal', $current_value = '' ) {

	// Defaults
	$values = array( 'placeholder' => '', 'min' => '', 'max' => '', 'field' => 'text', 'options' => array(), 'class' => '' );

	switch ( $condition ) :

		default:
		case 'subtotal' :
			$values['field'] = 'text';
			break;

		case 'subtotal_ex_tax' :
			$values['field'] = 'number';
			break;

		case 'tax' :
			$values['field'] = 'text';
			break;

		case 'quantity' :
			$values['field'] = 'number';
			break;

		case 'contains_product' :

			$values['field'] = 'select';

			$products = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'product', 'order' => 'asc', 'orderby' => 'title' ) );
			foreach ( $products as $product ) :
				$values['options'][$product->ID ] = $product->post_title;
			endforeach;

			break;

		case 'coupon' :
			$values['field'] = 'text';
			break;

		case 'weight' :
			$values['field'] = 'text';
			break;

		case 'contains_shipping_class' :

			$values['field'] 			= 'select';
			$values['options']['-1'] 	= __( 'No shipping class', 'woocommerce' );

			// Get all shipping classes
			foreach ( get_terms( 'product_shipping_class', array( 'hide_empty' => false ) ) as $shipping_class ) :
				$values['options'][ $shipping_class->slug ] 	= $shipping_class->name;
			endforeach;

			break;

		/**
		 * User details
		 */

		case 'zipcode' :
			$values['field'] = 'text';
			break;

		case 'city' :
			$values['field'] = 'text';
			break;

		case 'state' :

			$values['field'] = 'select';

			foreach ( WC()->countries->states as $country => $states ) :

				if ( empty( $states ) ) continue; // Don't show country if it has no states
				if ( ! array_key_exists( $country, WC()->countries->get_allowed_countries() ) ) continue; // Skip unallowed countries

				foreach ( $states as $state_key => $state ) :
					$country_states[ WC()->countries->countries[ $country ] ][ $country . '_' . $state_key ] = $state;
				endforeach;

				$values['options'] = $country_states;

			endforeach;

			break;

		case 'country' :

			$values['field'] 	= 'select';
			$values['options'] 	= WC()->countries->get_allowed_countries();

			break;

		case 'role' :

			$values['field'] = 'select';
			$roles = array_keys( get_editable_roles() );
			$values['options'] = array_combine( $roles, $roles );

			break;

		/**
		 * Product
		 */

		case 'width' :
			$values['field'] = 'text';
			break;

		case 'height' :
			$values['field'] = 'text';
			break;

		case 'length' :
			$values['field'] = 'text';
			break;

		case 'stock' :
			$values['field'] = 'text';
			break;

		case 'stock_status' :

			$values['field'] = 'select';
			$values['options'] = array(
				'instock' 		=> __( 'In stock', 'woocommerce' ),
				'outofstock'	=> __( 'Out of stock', 'woocommerce' ),
			);

			break;

		case 'category' :

			$values['field'] = 'select';

			$categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
			foreach ( $categories as $category ) :
				$values['options'][ $category->slug ] = $category->name;
			endforeach;

			break;


	endswitch;

	$values = apply_filters( 'was_values', $values, $condition );

	?><span class='was-value-wrap was-value-wrap-<?php echo $id; ?>'><?php

		switch ( $values['field'] ) :

			case 'text' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><input type='text' class='was-value <?php echo $classes; ?>' name='_was_shipping_method_conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'
					placeholder='<?php echo esc_attr( $values['placeholder'] ); ?>' value='<?php echo esc_attr( $current_value ); ?>'><?php

				break;

			case 'number' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><input type='text' class='was-value <?php echo $classes; ?>' name='_was_shipping_method_conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'
					min='<?php echo esc_attr( $values['min'] ); ?>' max='<?php echo esc_attr( $values['max'] ); ?>' placeholder='<?php echo esc_attr( $values['placeholder'] ); ?>'
					value='<?php echo esc_attr( $current_value ); ?>'><?php

				break;

			case 'select' :

				$classes = is_array( $values['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $values['class'] ) ) : sanitize_html_class( $values['class'] );
				?><select class='was-value <?php echo $classes; ?>' name='_was_shipping_method_conditions[<?php echo absint( $group ); ?>][<?php echo absint( $id ); ?>][value]'><?php

					foreach ( $values['options'] as $key => $value ) :

						if ( ! is_array( $value ) ) :
							?><option value='<?php echo esc_attr( $key ); ?>' <?php selected( $key, $current_value ); ?>><?php echo esc_attr( $value ); ?></option><?php
						else :
							?><optgroup label='<?php echo esc_attr( $key ) ?>'><?php
								foreach ( $value as $k => $v ) :
									?><option value='<?php echo esc_attr( $k ); ?>' <?php selected( $k, $current_value ); ?>><?php echo esc_attr( $v ); ?></option><?php
								endforeach;
							?></optgroup><?php

						endif;

					endforeach;

					if ( empty( $values['options'] ) ) :
						?><option readonly disabled><?php
							_e( 'There are no options available', 'woocommerce-advanced-shipping' );
						?></option><?php
					endif;

				?></select><?php

				break;

			default :
				do_action( 'was_condition_value_field_type_' . $values['field'], $values );
				break;

		endswitch;

	?></span><?php

}
