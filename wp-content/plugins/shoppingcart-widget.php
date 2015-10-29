<?php 
/*
Plugin Name: Shopping Cart Widget
Description: This widget displays shopping cart status and link
Version: 0.1
Author: Philippe Rey
*/

/* create settings menu */
add_action( 'admin_menu', 'ac_create_cart_menu' );
function ac_create_cart_menu() {
	add_submenu_page( 'options-general.php', 'Shopping Cart settings', 'Shopping Cart', 'administrator', __FILE__, 'ac_cart_settings_page' );
	add_action( 'admin_init', 'register_ac_cart_settings' );
}

/* register options */
function register_ac_cart_settings() {
	register_setting( 'ac-cart-settings-group', 'ac_cart_max_items' );
}

/* create settings page */
function ac_cart_settings_page() {
	?>
	<div class="wrap">
		<h2>Newsletter Subscription plugin settings</h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'ac-cart-settings-group' ); ?>
		    <?php do_settings_sections( 'ac-cart-settings-group' ); ?>
		    <table class="form-table">
		        <tr valign="top">
			        <th scope="row">Max number of items in cart</th>
			        <td><input type="number" name="ac_cart_max_items" value="<?php echo esc_attr( get_option( 'ac_cart_max_items' ) ); ?>" /></td>
		        </tr>         
		    </table>    
		    <?php submit_button(); ?>
		</form>
	</div>
<?php }
	
class Ac_Shopping_Cart_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'shopping_cart_widget',
			
			/* name of the widget */
			__('Shopping Cart', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Displays shopping cart status and link', 'ac' ) )
		);
	}
	
	function form( $instance ) {
		$defaults = array( 'title' => '-1' );
		$title = $instance[ 'title' ];
		
		/* markup for form */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}
	
	function widget( $args, $instance ) { 
		extract( $args ); ?>
		
		<div class="shopping-cart-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
			
			<?php
			global $woocommerce;
			$qty = $woocommerce->cart->get_cart_contents_count();
			$cart_url = $woocommerce->cart->get_cart_url();?>
			
			<input type="hidden" id="ac-miic" value="<?php echo base64_encode( get_option( 'ac_cart_max_items' ) ); ?>" />
			<div id='ac-shopping-cart-link' class="shopping-cart-link">
				<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
					<g>
						<polygon points="90.477,48 0.008,48 0.008,80 69.539,80 181.539,336 447.992,336 447.992,304 202.477,304 	"/>
						<path d="M160.008,112l64,160h223.984l64-160H160.008z M426.367,240H245.68l-38.406-96h257.469L426.367,240z"/>
						<path d="M240.008,368c-26.516,0-48,21.5-48,48s21.484,48,48,48c26.5,0,47.984-21.5,47.984-48S266.508,368,240.008,368z
							 M240.008,432c-8.828,0-16-7.188-16-16s7.172-16,16-16s16,7.188,16,16S248.836,432,240.008,432z"/>
						<path d="M399.992,368c-26.5,0-48,21.5-48,48s21.5,48,48,48s48-21.5,48-48S426.492,368,399.992,368z M399.992,432
							c-8.813,0-16-7.188-16-16s7.188-16,16-16s16,7.188,16,16S408.805,432,399.992,432z"/>
					</g>
				</svg>
				<div class="text">
					<?php echo "<span id='ac-shopping-cart-total'>" . $qty . "</span>&nbsp;<span id='ac-shopping-cart-items'>item" . ( ( $qty > 1 ) ? "s" : "" ) . "</span>"; ?>
					<span id='ac-shopping-cart-checkout-link'<?php echo ( $qty == 0 ) ? " style='visibility:hidden;'" : ""; ?>>
						- <a href="<?php echo $cart_url; ?>"><span class='ac-shopping-cart-view-cart'>View cart</span></a>
					</span>
				</div>
			</div>
		</div><?php
	}

}
?>
<?php
function ac_register_shopping_cart_widget() {
	register_widget( 'Ac_Shopping_Cart_Widget' );
}
add_action( 'widgets_init', 'ac_register_shopping_cart_widget' );
?>