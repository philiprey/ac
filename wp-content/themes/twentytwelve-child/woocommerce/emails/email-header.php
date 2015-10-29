<?php
/**
 * Email Header
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            	<tr>
                	<td align="center" valign="top">
						<div id="template_header_image">
	                		<?php
	                			if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
	                				echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
	                			}
	                		?>
						</div>
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Header -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header">
                                        <tr>
                                            <td id="header_wrapper">
	                                            <table border="0" cellpadding="0" cellspacing="10" width="600">
													<tr>
														<img src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/img/email/header.png'; ?>" alt="ATELIER CISEAUX" width="600px" usemap="#map1443397393061" />
														<map id="map1443397393061" name="map1443397393061"><area shape="rect" coords="0,0,251,32" title="" alt="ATELIER CISEAUX - Website" href="http://www.atelierciseaux.com" target="_blank"><area shape="rect" coords="379,0,412,31" title="" alt="ATELIER CISEAUX - Facebook" href="https://www.facebook.com/pages/Atelier-Ciseaux/91945685994" target="_blank"><area shape="rect" coords="416,0,448,32" title="" alt="ATELIER CISEAUX - Twitter" href="http://twitter.com/atelierciseaux" target="_blank"><area shape="rect" coords="453,0,487,33" title="" alt="ATELIER CISEAUX - Instagram" href="https://instagram.com/atelierciseaux/" target="_parent"><area shape="rect" coords="491,0,526,32" title="" alt="ATELIER CISEAUX - Bandcamp" href="https://atelierciseaux.bandcamp.com" target="_blank"><area shape="rect" coords="529,0,563,32" title="" alt="ATELIER CISEAUX - Soundcloud" href="http://soundcloud.com/atelierciseaux" target="_blank"><area shape="rect" coords="568,0,600,33" title="" alt="ATELIER CISEAUX - Youtube" href="https://www.youtube.com/channel/UCa3Jl66oGzoNSQYPp2tmYwg" target="_blank"></map>
													</tr>
	                                            </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                            <tr>
	                            <td id="header_post_image">
		                            <img src="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/img/email/surf.jpg'; ?>" alt="ATELIER CISEAUX" width="100%" />
	                            </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
	                                                        <h1><?php echo $email_heading; ?></h1>
                                                            <div id="body_content_inner">
