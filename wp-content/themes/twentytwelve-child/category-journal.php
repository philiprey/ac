<?php
/**
 * Journal category page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
?>

	<section id="primary" class="site-content journal-content">
		<div id="journal" role="main">
            <?php if ( have_posts() ) : ?>
                <?php
	                while ( have_posts() ) : the_post();
	                    get_template_part( 'content-journal', get_post_format() );
	                endwhile;
                ?>
            <?php else : ?>
                <?php get_template_part( 'content-journal', 'none' ); ?>
            <?php endif; ?>
            
            <?php twentytwelve_content_nav( 'nav-below' ); ?>    
		</div>
	</section>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>