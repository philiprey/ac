<?php
/**
 * Email Styles
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// website image folder link
$img_url = get_bloginfo( 'stylesheet_directory' ) . '/img/email/'; 
?>
#wrapper {
    background-color: <?php echo esc_attr( $bg ); ?>;
    margin: 0;
    padding: 70px 0 70px 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
}

#template_container {
    background-color: <?php echo esc_attr( $body ); ?>;
    border: 2px solid #000;
    border-radius: 3px !important;
}

#template_header {
    background-color: <?php echo esc_attr( $bg ); ?>;
    border-radius: 3px 3px 0 0 !important;
    color: <?php echo esc_attr( $base_text ); ?>;
    border-bottom: 0;
    line-height: 100%;
    vertical-align: middle;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1 {
    color: #000;
    font-size: 22px;
}

#template_footer td {
    padding: 0;
    -webkit-border-radius: 6px;
}

#template_footer #credit {
    border:0;
    color: <?php echo esc_attr( $base_text ); ?>;
    font-family: Arial;
    font-size:12px;
    line-height:125%;
    text-align:center;
    padding: 15px 48px;
}

#body_content {
    background-color: <?php echo esc_attr( $body ); ?>;
    padding-top: 20px;
}

#body_content table td {
    padding: 0;
}

#body_content table td td {
    padding: 12px;
}

#body_content table td th {
    padding: 12px;
}

#body_content p {
    margin: 0 0 16px;
}

#body_content_inner {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 150%;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
}

.text {
    color: <?php echo esc_attr( $text ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
    color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
    padding: 36px 48px;
    display: block;
}

#header_post_image {
	padding: 0;
	margin: 0;
}

h1 {
    color: <?php echo esc_attr( $base ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0 0 15px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    -webkit-font-smoothing: antialiased;
}

h2 {
    color: <?php echo esc_attr( $base ); ?>;
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
    color: <?php echo esc_attr( $base ); ?>;
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
    color: <?php echo esc_attr( $base ); ?>;
    font-weight: normal;
    text-decoration: underline;
}

img {
    border: none;
    display: inline;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
}
<?php
