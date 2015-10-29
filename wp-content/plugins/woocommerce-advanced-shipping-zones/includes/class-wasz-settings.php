<?php
/**
 * Class WASZ_Post_Type.
 *
 * Initialize the WASZ post type
 *
 * @class       WASZ_Post_Type
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Shipping
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WASZ_Settings {


	public function __construct() {

		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woocommerce_settings_tab' ), 40 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_shipping_zones', array( $this, 'woocommerce_settings_page' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_shipping_zones', array( $this, 'woocommerce_update_options' ) );

		// Shipping Zones table
		add_action( 'woocommerce_admin_field_shipping_zones_table', array( $this, 'generate_shipping_zones_table_html' ) );

	}


	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the Shipping Zones settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return array All WC settings tabs including newly added.
	 */
	public function woocommerce_settings_tab( $tabs ) {

		$tabs['shipping_zones'] = __( 'Shipping Zones', 'woocommerce-advanced-shipping' );

		return $tabs;

	}


	/**
	 * Settings.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of settings.
	 */
	public function woocommerce_get_settings() {

		$settings = apply_filters( 'woocommerce_shipping_zones_settings', array(

			array(
				'title' 	=> __( 'WooCommerce Advanced Shipping Zones', 'woocommerce-advanced-shipping' ),
				'type' 		=> 'title',
				'desc' 		=> '',
				'id' 		=> 'shipping_zones'
			),

			array(
				'title'   	=> __( 'Enable Shipping zones', 'woocommerce-advanced-shipping' ),
				'desc' 	  	=> __( '', 'woocommerce-advanced-shipping' ),
				'id' 	  	=> 'enable_shipping_zones',
				'default' 	=> 'yes',
				'type' 	  	=> 'checkbox',
				'autoload'	=> false
			),

			array(
				'title'   	=> __( 'Table', 'woocommerce-advanced-shipping' ),
				'id' 	  	=> 'shipping_zones_table',
				'type' 	  	=> 'shipping_zones_table',
			),

			array(
				'type' 		=> 'sectionend',
				'id' 		=> 'shipping_zones_end'
			),


		) );

		return $settings;

	}


	/**
	 * Zones table.
	 *
	 * Load and render the shipping zones table.
	 *
	 * @return string
	 */
	public function generate_shipping_zones_table_html() {

		ob_start();

			/**
			 * Shipping zones table
			 */
			require plugin_dir_path( __FILE__ ) . 'shipping-zones-table.php';

		echo ob_get_clean();

	}


	/**
	 * validate_additional_shipping_zones_table_field function.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function validate_additional_shipping_zones_table_field( $key ) {
		return false;
	}



	/**
	 * Settings page content.
	 *
	 * Output settings page content via WooCommerce output_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_settings_page() {

		WC_Admin_Settings::output_fields( $this->woocommerce_get_settings() );

	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_update_options() {

		WC_Admin_Settings::save_fields( $this->woocommerce_get_settings() );

	}


}
