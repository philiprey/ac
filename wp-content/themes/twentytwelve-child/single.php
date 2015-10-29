<?php
/**
 * Single News & Journal post page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
?>
	
	<section id="primary" class="site-content news-content single">
		<div id="news" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h2><?php the_time('d F Y'); ?></h2>
					</header>
					
					<hr class="small-line" />
					
					<div class="entry-content">
						<?php echo the_post_thumbnail('single-news-thumb'); ?>
						<?php the_content(); ?>
						<?php echo do_shortcode( '[ssba]' ); ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</section>

<?php get_footer(); ?>