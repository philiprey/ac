<?php
/**
 * Releases content
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
 
global $product;
$special_link = get_field( 'release-special_link' );		
?>
	
	<li class="release-bloc<?php echo $first ? " first":""; ?>">
		<?php if ( empty( $special_link ) ): ?>
			<?php $status = get_post_status(); ?>
			<?php /* if ( $status == "future" ) : */ ?>
				<!-- <a href="<?php echo get_site_url() . $permalink_base . "/" . $post->post_name; ?>"> -->
			<?php /* else: */ ?>
				<a href="<?php the_permalink(); ?>">
			<?php /* endif; */ ?>
		<?php else: ?>
			<a href="<?php echo $special_link; ?>" target="blank">
		<?php endif; ?>
			<header class="entry-header">
				<?php echo the_post_thumbnail( 'releases-grid-thumb' ); ?>
			</header>
			<div class="entry-content">
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
					<?php if ( !empty( $title ) ) : ?>
	        			<span class="title"><?php echo $title; ?></span>
	        		<?php else: ?>
						&nbsp;        			
					<?php endif; ?>
				</h3>
				<hr class="small-line" />
        	</div>
		</a>
	</li>

