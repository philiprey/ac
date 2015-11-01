<?php 
/*
Plugin Name: Last Releases Widget
Description: This widget gets the latest in stock WooCommerce products
Version: 0.1
Author: Philippe Rey
*/

class Ac_Last_Releases_Widget extends WP_Widget {
	public $max_releases = 4;
	
	function __construct() {
		parent::__construct(
			/* base ID of the widget */
			'last_releases_widget',
			
			/* name of the widget */
			__('Last Releases', 'ac' ),
			
			/* widget options */
			array ( 'description' => __( 'Gets last in stock releases', 'ac' ) )
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
		
		<div class="last-releases-widget">
			<header class="entry-header"><h2><?php echo $instance[ 'title' ]; ?></h2></header>
			<hr class='small-line' />
			
			<?php
			/* get last 3 releases */
			$release_ct = 0;
			query_posts( 
				array( 
					'post_type' => 'product', 
					'posts_per_page' => $this->max_releases * 2, 
					'product_cat' => '7inches,10inches,12inches,other,tape,digital'
				)
			);
			while ( have_posts() ) : the_post();?>
				<?php
				/* skip non-orphane digital releases */
				$post_ID = get_the_ID();
				$sku = get_product_sku_by_id( $post_ID );
				if ( substr( $sku, -1 ) == 'd' ) {
					if ( ! is_null( get_product_id_by_sku( substr( $sku, 0, -1 ) ) ) ) {
						continue;
					}
				}
				$release_ct++;
				
				/* set link */
				$external_link = get_field( 'release-external_buy_link' );
				if ( empty( $external_link ) ) {
					$link = get_permalink( $post_ID );	
				} else {
					$link = '/shop#' . $post_ID;
				}
				
				/* check release count */
				if ( $release_ct == $this->max_releases + 1 ) { break; }
				?>
				<a href="<?php echo $link; ?>">
					<?php echo the_post_thumbnail( 'last-releases-thumb' ); ?>
					<div class="details">
						<?php 
							$artists = array();
							$data = get_field( 'product-artists' );
							foreach ( $data as $d ) {
								$artists[] = get_the_title( $d->ID );
							}
							
							$title = get_release_attribute_value( $post->ID, 'release-title' );
						?>
						<h3>
							<span class="artist"><?php echo implode( " & ", $artists ); ?></span><br />
							<?php if ( ! empty( $title ) ): ?>
			        			<span class="title"><?php echo $title; ?></span>
			        		<?php else: ?>
								&nbsp;        			
							<?php endif; ?>
						</h3>
						<hr class="small-line" />
		        	</div>
	        	</a>
			<?php endwhile; ?>
		</div><?php
	}

}
?>
<?php
function ac_register_last_releases_widget() {
	register_widget( 'Ac_Last_Releases_Widget' );
}
add_action( 'widgets_init', 'ac_register_last_releases_widget' );
?>