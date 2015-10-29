<?php 
/*
Plugin Name: Soundcloud Player Widget
Description: This widget displays a Soundcloud player
Version: 0.1
Author: Philippe Rey
*/

class Ac_Soundcloud_Player_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'soundcloud_player_widget',
			
			/* name of the widget */
			__('Soundcloud Player', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Displays a Soundcloud player', 'ac' ) )
		);
	}
	
	function form( $instance ) {
		$defaults = array( 'soundcloud_set' => '-1' );
		$soundcloud_set = $instance[ 'soundcloud_set' ];
		
		/* markup for form */ ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'soundcloud_set' ); ?>">Soundcloud Playlist:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'soundcloud_set' ); ?>" name="<?php echo $this->get_field_name( 'soundcloud_set' ); ?>" value="<?php echo esc_attr( $soundcloud_set ); ?>">
		</p>
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'soundcloud_set' ] = strip_tags( $new_instance[ 'soundcloud_set' ] );
		return $instance;
	}
	
	function widget( $args, $instance ) { 
		extract( $args );
		
		$soundcloud_set = $instance[ 'soundcloud_set' ];
		?>
		
		<div class="soundcloud-player-widget">
			<a href="http://soundcloud.com/atelierciseaux/sets/<?php echo $soundcloud_set; ?>" class="sc-player"></a>
		</div>
		
		<?php
	}

}
?>
<?php
function ac_register_soundcloud_player_widget() {
	register_widget( 'Ac_Soundcloud_Player_Widget' );
}
add_action( 'widgets_init', 'ac_register_soundcloud_player_widget' );
?>