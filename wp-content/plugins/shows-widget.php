<?php 
/*
Plugin Name: Shows List Widget
Description: This widget gets all the shows
Version: 0.1
Author: Philippe Rey
*/

class Ac_Shows_List_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'shows_list_widget',
			
			/* name of the widget */
			__('Shows List', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Gets all shows', 'ac' ) )
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
		
		<div class="shows-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
		
			<?php		
			/* get all future & today shows */
			query_posts( 
				array( 
					'post_type' => 'ac_show', 
					'posts_per_page' => -1,
					'orderby' => 'date',
					'order' => 'ASC',
					'post_status' => array( 'scheduled' ),
					'date_query' => array(
						'column' => 'post_date',
						'after' => '- 1 days'
					)
				)
			);
			$shows = array();
			while ( have_posts() ) : the_post();
				$data = array_shift( get_field( 'ac-show-artist' ) );
				$artist = get_the_title( $data->ID );
				$shows[ $artist ][] = array(
					'date' => get_the_time( 'd.m.y' ), 
					'venue' => get_field( 'ac-show-venue' ), 
					'town' => get_field( 'ac-show-town' ), 
					'country' => get_field( 'ac-show-country' )
				);
			endwhile;
			ksort( $shows );
			$first = true;?>
			<div class="shop-sidebar">
				<?php foreach ( $shows as $artist => $data ): ?>
					<p class="sub-title<?php echo $first ? "": " not-first"; ?>"><?php echo $artist; ?></p>
					<?php $first = false; ?>
					<?php foreach ( $data as $details ): ?>
						<p class="show"><?php echo $details[ 'date' ] . " - " . $details[ 'venue' ]; ?></p>
						<p class="location"><?php echo $details[ 'town' ] . ", "  . $details[ 'country' ]; ?></p>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div><?php 
		
		/* delete past shows */			
		query_posts( 
			array( 
				'post_type' => 'ac_show', 
				'posts_per_page' => -1,
				'date_query' => array(
					'column' => 'post_date',
					'before' => 'today'
				)
			)
		);
		while ( have_posts() ) : the_post();
			wp_delete_post( get_the_ID(), true );
		endwhile;
	}

}
?>
<?php
function ac_register_shows_list_widget() {
	register_widget( 'Ac_Shows_List_Widget' );
}
add_action( 'widgets_init', 'ac_register_shows_list_widget' );
?>