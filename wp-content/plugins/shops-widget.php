<?php 
/*
Plugin Name: Shops List Widget
Description: This widget gets all the shops
Version: 0.1
Author: Philippe Rey
*/

class Ac_Shops_List_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'shops_list_widget',
			
			/* name of the widget */
			__('Shops List', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Gets all shops', 'ac' ) )
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
		
		<div class="shops-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
		
			<?php		
			/* get all shops */
			query_posts( 
				array( 
					'post_type' => 'ac_shop', 
					'posts_per_page' => -1,
					'orderby' => 'title',
					'order' => 'ASC'
				)
			);?>
			<div class="shop-sidebar">
				<?php while ( have_posts() ) : the_post(); ?>
					<p>
						<?php $link = get_field( 'ac-shop-link' ); ?>
						<?php if ( ! empty ( $link ) ): ?>
							<a href="<?php echo $link; ?>" target="_blank">
						<?php endif; ?>			
						<?php the_title(); ?>
						<?php $country = get_field( 'ac-shop-country' ); ?>
						<?php if ( ! empty( $country ) ): ?>
							&nbsp;(<?php echo $country; ?>)
						<?php endif; ?>
						<?php if ( ! empty ( $link ) ): ?>
							</a>
						<?php endif; ?>
					</p>
				<?php endwhile; ?>
			</div>
		</div><?php 
	}

}
?>
<?php
function ac_register_shops_list_widget() {
	register_widget( 'Ac_Shops_List_Widget' );
}
add_action( 'widgets_init', 'ac_register_shops_list_widget' );
?>