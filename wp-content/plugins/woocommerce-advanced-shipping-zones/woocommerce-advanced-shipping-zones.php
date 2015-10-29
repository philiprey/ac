<?PHP
/*
Plugin Name: Woocommerce Advanced Shipping Zones
Plugin URI: http://www.jeroensormani.com/
Donate link: http://www.jeroensormani.com/donate/
Description: WooCommerce Advanced Shipping Zones is an extension to add Shipping Zones to WooCommerce Advanced Shipping
Version: 1.0.0
Author: Jeroen Sormani
Author URI: http://www.jeroensormani.com/
Text Domain: woocommerce-advanced-shipping-zones


/**
 * Copyright Jeroen Sormani
 *	Class WooCommerce_Advanced_Shipping_Zones
 *
 *	Main WAS class, add filters and handling all other files
 *
 *	@class       WooCommerce_Advanced_Shipping_Zones
 *	@version     1.0.0
 *	@author      Jeroen Sormani
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WooCommerce_Advanced_Shipping_Zones {


	/**
	 * Instace of WooCommerce_Advanced_Shipping.
	 *
	 * @since 1.0.1
	 * @access private
	 * @var object $instance The instance of WAS.
	 */
	private static $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
		    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

		// Check if WooCommerce is active
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) :
				return;
			endif;
		endif;

		// Check if WooCommerce Advanced Shipping is active
		if ( ! in_array( 'woocommerce-advanced-shipping/woocommerce-advanced-shipping.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce-advanced-shipping/woocommerce-advanced-shipping.php' ) ) :
				return;
			endif;
		endif;


		// Add zone to conditions list
		add_filter( 'was_conditions', array( $this, 'was_conditions_add_zones' ), 10, 1 );

		// Add zone to values list
		add_filter( 'was_values', array( $this, 'was_values_add_zones' ), 10, 2 );

		// Add description to zone condition
		add_filter( 'was_descriptions', array( $this, 'was_descriptions_zones' ) );

		// Match zones
		add_filter( 'was_match_condition_zones', array( $this, 'was_match_condition_zones' ), 10, 3 );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );


		// Settings page
		require_once 'includes/class-wasz-settings.php';
		$this->settings = new WASZ_Settings();

		// Post type
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wasz-post-type.php';
		$this->post_type = new WASZ_Post_Type();

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.1
	 *
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue style and java scripts.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {

		// Only load scripts on relvant pages
		if (
			( isset( $_REQUEST['post'] ) && 'shipping_zone' == get_post_type( $_REQUEST['post'] ) ) ||
			( isset( $_REQUEST['post_type'] ) && 'shipping_zone' == $_REQUEST['post_type'] ) ||
			( isset( $_REQUEST['tab'] ) && 'shipping_zones' == $_REQUEST['tab'] )
		) :

			// Style script
			wp_enqueue_style( 'woocommerce-advanced-shipping-zones', plugins_url( 'assets/css/woocommerce-advanced-shipping-zones.css', __FILE__ ), array() );

			wp_enqueue_style( 'woocommerce-advanced-shipping-css', plugins_url( 'assets/admin/css/woocommerce-advanced-shipping.css', WAS()->file ), array(), WAS()->version );

			// Chosen script
			wp_enqueue_script( 'chosen', WC()->plugin_url() . '/assets/js/chosen/chosen.jquery.min.js', array('jquery'), WC_VERSION );

			// Chosen style
			wp_enqueue_style( 'woocommerce_chosen_styles', str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/css/chosen.css' );

			// Set chosen
			wc_enqueue_js( '$(".chosen").chosen({search_contains: true});' );

		endif;

	}


	/**
	 * Zone condition.
	 *
	 * Add the shipping zone to the conditions list.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array $conditions 	List existing of conditions.
	 * @return 	array				List of modified conditions including zones.
	 */
	public function was_conditions_add_zones( $conditions ) {

		$conditions['General']['zones'] = __( 'Shipping zone', 'woocommerce-advanced-shipping' );

		return $conditions;

	}


	/**
	 * Zone value.
	 *
	 * Add zones to the condition values.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array $values 		Current values (empty).
	 * @param 	array $condition	Current condition.
	 * @return 	array				Values.
	 */
	public function was_values_add_zones( $values, $condition ) {

		switch ( $condition ) {

			case 'zones':

				$values['field'] 		= 'select';
				$values['options'] 		= array();

				$zone_args = array(
					'posts_per_page'	=> '-1',
					'post_type'			=> 'shipping_zone',
				);
				$zones = get_posts( $zone_args );
				foreach ( $zones as $zone ) :
					$values['options'][ $zone->ID ] = $zone->post_title;
				endforeach;

			break;

		}

		return $values;

	}


	/**
	 * Zone description.
	 *
	 * Add a description for the shipping zones.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array $descriptions List existing of descriptions.
	 * @return 	array				List of modified descriptions including zones.
	 */
	public function was_descriptions_zones( $descriptions ) {

		$descriptions['zones'] = sprintf( __( 'Zones can be added through the \'Shipping zones\' page', 'woocommerce-advanced-shipping' ), '' );

		return $descriptions;

	}


	/**
	 * Must match given zone.
	 *
	 * @param bool $match
	 * @param string $operator
	 * @param mixed $value
	 * @return bool
	 */
	public function was_match_condition_zones( $match, $operator, $value ) {

		if ( ! isset( WC()->customer ) ) :
			return;
		endif;

		$zone = get_post( $value );

		// Stop if post is invalid or not shipping_zone
		if ( ! $zone || 'shipping_zone' != $zone->post_type  ) :
			return;
		endif;

		$country_match 	= false;
		$state_match 	= false;
		$zipcode_match 	= false;

		$countries 	= (array) get_post_meta( $zone->ID, '_countries', true );
		$states	 	= (array) get_post_meta( $zone->ID, '_states', true );
		$zipcodes 	= (array) preg_split('/[\n\,]+/', get_post_meta( $zone->ID, '_zipcodes', true ) );

		// Remove all non- letters and numbers
		foreach ( $zipcodes as $key => $zipcode ) :
			$zipcodes[ $key ] = preg_replace( '/[^0-9a-zA-Z\-]/', '', $zipcode );
		endforeach;

		$user_country 	= WC()->customer->get_shipping_country();
		$user_state 	= WC()->customer->get_shipping_country() . '_' . WC()->customer->get_shipping_state();
		$user_zipcode	= WC()->customer->get_shipping_postcode();

		if ( '==' == $operator ) :

			$country_match 	= in_array( $user_country, $countries ) && ! empty( $user_country );
			$state_match 	= in_array( $user_state, $states ) && ! empty( $user_state );
			$zipcode_match 	= in_array( $user_zipcode, $zipcodes ) && ! empty( $user_zipcode );

			foreach ( $zipcodes as $zipcode ) :

				if ( empty( $zipcode ) ) :
					continue;
				endif;

				if ( $zipcode_match == true ) :
					break;
				endif;

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) :
					$zipcode_match = ( $user_zipcode >= min( $parts ) && $user_zipcode <= max( $parts ) );
				else :
					$zipcode_match = preg_match( "/^" . preg_quote( $zipcode ) . "/i", $user_zipcode );
				endif;

			endforeach;

			if ( true == $country_match || true == $state_match || true == $zipcode_match ) :
				$match = true;
			endif;

		elseif ( '!=' == $operator ) :

			$match = true;

			$country_match 	= in_array( $user_country, $countries );
			$state_match 	= in_array( $user_state, $states );
			$zipcode_match 	= ! empty( $user_zipcode ) && in_array( $user_zipcode, $zipcodes );

			foreach ( $zipcodes as $zipcode ) :

				if ( empty( $zipcode ) ) :
					continue;
				endif;

				if ( $zipcode_match == true ) :
					break;
				endif;

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) :
					$zipcode_match = ( $user_zipcode >= min( $parts ) && $user_zipcode <= max( $parts ) );
				else :
					$zipcode_match = preg_match( "/^" . preg_quote( $zipcode ) . "/i", $user_zipcode );
				endif;

			endforeach;

			if ( true == $country_match || true == $state_match || true == $zipcode_match ) :
				$match = false;
			endif;

		endif;

		return $match;

	}


}


/**
 * The main function responsible for returning the WooCommerce_Advanced_Shipping_Zones object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php WASZ()->method_name(); ?>
 *
 * @since 1.0.1
 *
 * @return object WooCommerce_Advanced_Shipping_Zones class object.
 */
if ( ! function_exists( 'WASZ' ) ) :

 	function WASZ() {
		return WooCommerce_Advanced_Shipping_Zones::instance();
	}

endif;

WASZ();
