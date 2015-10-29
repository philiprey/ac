<?php
/**
 * News category page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
?>

	<section id="primary" class="site-content news-content">
		<div id="news" role="main">
            <?php if ( have_posts() ) : ?>
                <?php
	                while ( have_posts() ) : the_post();
	                    get_template_part( 'content-news', get_post_format() );
	                endwhile;
                ?>
            <?php else : ?>
                <?php get_template_part( 'content-news', 'none' ); ?>
            <?php endif; ?>
            
            <?php twentytwelve_content_nav( 'nav-below' ); ?>    
		</div>
	</section>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>

