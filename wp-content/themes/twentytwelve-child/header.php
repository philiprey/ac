<?php
/**
 * Header
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
<?php
$body_class = '';
if ( is_shop() ) { 
	$body_class = 'ac-shop';
} elseif ( is_cart() ) {
	$body_class = 'ac-cart';
} elseif ( is_checkout() ) {
	$body_class = 'ac-checkout';
} 
?>
</head>

<body <?php body_class( $body_class ); ?>>
<input type="hidden" id="template_path" value="<?php echo get_bloginfo('stylesheet_directory') ?>" />
<div id="page" class="hfeed site">
	
	<!-- pre-header -->
	<div class="ac-pre-header">
		<!-- title -->
		<div class="ac-title"><a href="<?php echo get_home_url(); ?>"><h1>A T E L I E R C I S E A U X</h1></a></div>
		
		<!-- social icons -->
		<?php get_template_part( 'social' ); ?>
    </div>
    
	<header id="masthead" class="site-header" role="banner">
		
		<!-- background image -->
		<div class="header-frame">
			
			<!-- white frame -->
			<div class="header-frame-circle">
				
				<!-- main menu -->
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<?php 
						wp_nav_menu( array(
                            'container'       => 'div',
                            'theme_location'  => 'primary',
                            'menu_class'      => 'nav-menu',
                            'menu_id'         => 'ac-main-menu'
                       ) ); 
					?>
				</nav>
		
			</div>
			
		</div>
		
	</header>

	<div id="main" class="wrapper">