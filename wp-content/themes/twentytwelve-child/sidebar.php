<?php
/**
 * Main sidebar
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php
			if ( is_category() ) {
				$category = get_query_var( 'cat' );
				$current_category = get_category( $cat );
				$category_slug = $current_category->slug;
				if ( ! empty( $category_slug ) ) {
					$class = " " . $category_slug . "-sidebar";
				}
			} elseif ( is_shop() ) {
				$class = " shop-sidebar";
			} else {
				$class = " " . basename( get_permalink() ) . "-sidebar";
			}
		?>
		<div id="secondary" class="widget-area ac-sidebar<?php echo $class; ?>" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	<?php endif; ?>