<?php 
/*
Plugin Name: Newsletter subscription Widget
Description: This widget displays a newsletter subscription (through Elastic Email form)
Version: 0.1
Author: Philippe Rey
*/

/* create settings menu */
add_action( 'admin_menu', 'ac_create_nl_menu' );
function ac_create_nl_menu() {
	add_submenu_page( 'options-general.php', 'Newsletter Subscription settings', 'Newsletter Subscription', 'administrator', __FILE__, 'ac_nl_settings_page' );
	add_action( 'admin_init', 'register_ac_nl_settings' );
}

/* register options */
function register_ac_nl_settings() {
	register_setting( 'ac-nl-settings-group', 'ac_nl_api_key' );
	register_setting( 'ac-nl-settings-group', 'ac_nl_list_id' );
}

/* create settings page */
function ac_nl_settings_page() {
	?>
	<div class="wrap">
		<h2>Newsletter Subscription plugin settings</h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'ac-nl-settings-group' ); ?>
		    <?php do_settings_sections( 'ac-nl-settings-group' ); ?>
		    <table class="form-table">
		        <tr valign="top">
			        <th scope="row">API Key</th>
			        <td><input type="text" name="ac_nl_api_key" value="<?php echo esc_attr( get_option( 'ac_nl_api_key' ) ); ?>" /></td>
		        </tr>         
		        <tr valign="top">
			        <th scope="row">List ID</th>
			        <td><input type="number" name="ac_nl_list_id" value="<?php echo esc_attr( get_option( 'ac_nl_list_id' ) ); ?>" /></td>
		        </tr>
		    </table>    
		    <?php submit_button(); ?>
		</form>
	</div>
<?php }

class Ac_Newletter_Subscribe_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'newsletter_subscription_widget',
			
			/* name of the widget */
			__('Newsletter Subscribe', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Displays newsletter subscription form', 'ac' ) )
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
		
		<div class="nl-subscribe-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
			
			<?php
				/* get API url */
				$api_key = get_option( 'ac_nl_api_key' );
				$list_id = get_option( 'ac_nl_list_id' );
				$url = "https://api.elasticemail.com/v2/contact/quickadd?apikey=" . $api_key . "&email=[email]&listid=" . $list_id . "&status=Active";
			?>
			<form id="ac-nl-subscribe" name="ac-nl-subscribe" action="<?php echo $url; ?>" method="GET">
				<input class="email" name="email" type="email" id="ac-nl-email" placeholder="Your email" />
				<a id="ac-nl-submit" class="submit" href="javascript:void(0)" onclick="nlFormSubmit()"><span>Go</span></a>
			</form>
			<script>$j('#ac-nl-subscribe').validate();</script>
			<div class="ac-nl-messages"></div>
		</div><?php 
	}

}
?>
<?php
function ac_register_newletter_subscribe_widget() {
	register_widget( 'Ac_Newletter_Subscribe_Widget' );
}
add_action( 'widgets_init', 'ac_register_newletter_subscribe_widget' );
?>