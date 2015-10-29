<?php
/**
 * Releases category page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
query_posts( 
	array( 
		'post_type' => 'product', 
		'posts_per_page' => -1, 
		'product_cat' => '7inches,10inches,12inches,other,tape'
	) 
);

$release_ct = 1;
$release_per_line = 3;
$first = false;

$permalink_settings = get_option( 'woocommerce_permalinks' );
$permalink_base = $permalink_settings['product_base'];
?>

	<section id="primary" class="site-content releases-content">
		<ul id="releases" class="release-grid" role="main">
            <?php if ( have_posts() ) : ?>
            	<?php
	                while ( have_posts() ) : the_post();
	                    include( locate_template( 'content-releases.php' ) );
	                    if ($release_ct == $release_per_line) {
		                    $release_ct = 1;
		                    $first = true;
	                    } else {
		                    echo "<li class=\"release-gap\">&nbsp;</li> \n";
		                    $release_ct++;
		                    $first = false;
	                    }
	                endwhile;
                ?>
            <?php else : ?>
                <?php get_template_part( 'content-releases', 'none' ); ?>
            <?php endif; ?>
		</ul>
	</section>

<?php get_footer(); ?>

