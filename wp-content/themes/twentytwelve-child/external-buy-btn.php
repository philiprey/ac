<?php
/**
 * External buy button for Shop
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
 
$external_buy_from = get_field( 'release-external_buy_from' ); 
$external_buy_from_text = "";
if ( ! empty( $external_buy_from ) ) {
	$external_buy_from_text = " from " . $external_buy_from;	
}
?>

<a class="button add_to_cart_button product_type_simple" rel="nofollow" href="<?php echo $external_buy_link; ?>" style="opacity: 1;" target="_blank">Buy<?php echo $external_buy_from_text; ?></a>