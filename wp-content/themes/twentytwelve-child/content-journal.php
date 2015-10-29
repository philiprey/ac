<?php
/**
 * Journal content
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h2><?php the_time('d F Y'); ?></h2>
		</header>
		
		<hr class="small-line" />
		
		<div class="entry-content">
			<?php echo the_post_thumbnail( 'news-thumb' ); ?>
			<?php the_content(); ?>
		</div>
	</article>