<?php 
/*
Plugin Name: Articles Widget
Description: This widget gets all the articles
Version: 0.1
Author: Philippe Rey
*/
?>
<?php
class Ac_Articles_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'articles_widget',
			
			/* name of the widget */
			__('Articles', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Gets all articles', 'ac' ) )
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
		
		<div class="articles-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
		
			<?php
			/* get all articles */
			query_posts( 
				array( 
					'post_type' => 'ac_article', 
					'posts_per_page' => -1
				)
			);
			$current_year = ''; ?>
			<div class="articles">
				<?php while ( have_posts() ) : the_post(); ?>			
					<?php 
					$link = get_field( 'ac-article-link' );
					$year = get_the_date( 'Y' );
					if ( $current_year != $year ): ?>
						<h3<?php if ( empty( $current_year ) ) { echo " class='first'"; } ?>><?php echo $year; ?></h3>
						<?php $current_year = $year;
					endif; ?>
					<p>
						<?php if ( ! empty ( $link ) ): ?>
							<a href="<?php echo $link; ?>" target="_blank">
						<?php endif; ?>
						<?php the_title(); ?>
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
function ac_register_articles_widget() {
	register_widget( 'Ac_Articles_Widget' );
}
add_action( 'widgets_init', 'ac_register_articles_widget' );
?>