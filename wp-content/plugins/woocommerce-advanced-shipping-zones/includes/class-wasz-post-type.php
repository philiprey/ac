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

class WASZ_Post_Type {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		 // Add/save meta boxes
		 add_action( 'add_meta_boxes', array( $this, 'post_type_meta_box' ) );
		 add_action( 'save_post', array( $this, 'save_meta' ) );

		 // Edit user notices
		 add_filter( 'post_updated_messages', array( $this, 'custom_post_type_messages' ) );

		 // Redirect after delete
		 add_action('load-edit.php', array( $this, 'redirect_after_trash' ) );

	}


	/**
	 * Register post type.
	 *
	 * Register the Zones post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
		    'name' 					=> __( 'Shipping zone', 'woocommerce-advanced-shipping' ),
			'singular_name' 		=> __( 'Shipping zone', 'woocommerce-advanced-shipping' ),
		    'add_new' 				=> __( 'Add New', 'woocommerce-advanced-shipping' ),
		    'add_new_item' 			=> __( 'Add New Shipping zone' , 'woocommerce-advanced-shipping' ),
		    'edit_item' 			=> __( 'Edit Shipping zone' , 'woocommerce-advanced-shipping' ),
		    'new_item' 				=> __( 'New Shipping zone' , 'woocommerce-advanced-shipping' ),
		    'view_item' 			=> __( 'View Shipping zone', 'woocommerce-advanced-shipping' ),
		    'search_items' 			=> __( 'Search Shipping zones', 'woocommerce-advanced-shipping' ),
		    'not_found' 			=> __( 'No Shipping zones found', 'woocommerce-advanced-shipping' ),
		    'not_found_in_trash'	=> __( 'No Shipping zones found in Trash', 'woocommerce-advanced-shipping' ),
		);

		register_post_type( 'shipping_zone', array(
			'label' 				=> 'shipping_zone',
			'show_ui' 				=> true,
			'show_in_menu' 			=> false,
			'publicly_queryable' 	=> false,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'rewrite' 				=> array( 'slug' => 'shipping_zone', 'with_front' => true ),
			'_builtin' 				=> false,
			'query_var' 			=> true,
			'supports' 				=> array( 'title' ),
			'labels' 				=> $labels,
		) );

	}


	/**
	 * Messages.
	 *
	 * Modify the notice messages text for the 'shipping_zone' post type.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array $messages Existing list of messages.
	 * @return 	array			Modified list of messages.
	 */
	function custom_post_type_messages( $messages ) {

		$post 				= get_post();
		$post_type			= get_post_type( $post );
		$post_type_object	= get_post_type_object( $post_type );

		$messages['shipping_zone'] = array(
			0  => '',
			1  => __( 'Shipping zone updated.', 'woocommerce-advanced-shipping' ),
			2  => __( 'Custom field updated.', 'woocommerce-advanced-shipping' ),
			3  => __( 'Custom field deleted.', 'woocommerce-advanced-shipping' ),
			4  => __( 'Shipping zone updated.', 'woocommerce-advanced-shipping' ),
			5  => isset( $_GET['revision'] ) ?
				sprintf( __( 'Shipping zone restored to revision from %s', 'woocommerce-advanced-shipping' ), wp_post_revision_title( (int) $_GET['revision'], false ) )
				: false,
			6  => __( 'Shipping zone published.', 'woocommerce-advanced-shipping' ),
			7  => __( 'Shipping zone saved.', 'woocommerce-advanced-shipping' ),
			8  => __( 'Shipping zone submitted.', 'woocommerce-advanced-shipping' ),
			9  => sprintf(
				__( 'Shipping zone scheduled for: <strong>%1$s</strong>.', 'woocommerce-advanced-shipping' ),
				date_i18n( __( 'M j, Y @ G:i', 'woocommerce-advanced-shipping' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Shipping zone draft updated.', 'woocommerce-advanced-shipping' ),
		);

		$permalink = admin_url( '/admin.php?page=wc-settings&tab=shipping_zones' );
		$overview_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'Return to overview.', 'woocommerce-advanced-shipping' ) );
		$messages['shipping_zone'][1] .= $overview_link;
		$messages['shipping_zone'][6] .= $overview_link;
		$messages['shipping_zone'][9] .= $overview_link;
		$messages['shipping_zone'][8]  .= $overview_link;
		$messages['shipping_zone'][10] .= $overview_link;

		return $messages;

	}


	/**
	 * Meta boxes.
	 *
	 * Add two meta boxes to the 'was' post type.
	 *
	 * @since 1.0.0
	 */
	public function post_type_meta_box() {

		add_meta_box( 'shipping_zone', __( 'Shipping zone', 'woocommerce-advanced-shipping' ), array( $this, 'meta_box_output' ), 'shipping_zone', 'normal' );

	}


	/**
	 * Render meta box.
	 *
	 * Get conditions meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function meta_box_output() {

		/**
		 * Load meta box contents
		 */
		require_once plugin_dir_path( __FILE__ ) . 'meta-box-shipping-zones.php';

	}


	/**
	 * Render meta box.
	 *
	 * Get settings meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function meta_box_settings_output() {

		/**
		 * Load meta box settings view
		 */
		require_once plugin_dir_path( __FILE__ ) . 'meta-box-shipping-zones.php';

	}


	/**
	 * Save meta.
	 *
	 * Validate and save post meta. This value contains all
	 * the normal shipping method settings (no conditions).
	 *
	 * @since 1.0.0
	 *
	 * @param int/numberic $post_id ID of the post being saved.
	 */
	 public function save_meta( $post_id ) {

		if ( !isset( $_POST['shipping_zones_meta_box_nonce'] ) ) :
			return $post_id;
		endif;

		$nonce = $_POST['shipping_zones_meta_box_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'shipping_zones_meta_box' ) ) :
			return $post_id;
		endif;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
			return $post_id;
		endif;

		if ( ! current_user_can( 'manage_woocommerce' ) ) :
			return $post_id;
		endif;

		$countries 	= isset( $_POST['countries'] ) 	? $_POST['countries'] 	: '';
		$states		= isset( $_POST['states'] ) 	? $_POST['states']	 	: '';

		update_post_meta( $post_id, '_countries', $countries );
		update_post_meta( $post_id, '_states', $states );
		update_post_meta( $post_id, '_zipcodes', $_POST['zipcodes'] );

	}


	/**
	 * Redirect trash.
	 *
	 * Redirect user after trashing a WCAM post.
	 *
	 * @since 1.0.0
	 */
	public function redirect_after_trash() {

		$screen = get_current_screen();

		if( 'edit-wcam' == $screen->id ) :

			if( isset( $_GET['trashed'] ) &&  intval( $_GET['trashed'] ) > 0 ) :

				$redirect = admin_url( '/admin.php?page=wc-settings&tab=messages' );
				wp_redirect( $redirect );
				exit();

			endif;

		endif;

	}


}
