<?php
/**
 * Check current download code
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $wpdb;

/* get release */
/*
$code = trim( $_POST[ 'code' ] );
$obj = $wpdb->get_row( 'SELECT a.`title` FROM ' . dc_tbl_releases() . ' a LEFT JOIN ' . dc_tbl_codes() . ' b ON b.`release` = a.`ID` WHERE b.`code_suffix` = "' . $code . '"' );

if ( empty( $obj ) ) {
	return false;
}

print_r($obj);
*/

$return = array(
	'error_message' => ''
);

/* get submitted code */
$post_code = strtoupper( trim( $_POST[ 'code' ] ) );
		
		
		//$submitted_release = ( $_POST['submitted_release_' . $shortcode_id] != '' ? $_POST['submitted_release_' . $shortcode_id] : 'all' );
		//$post_code = strtoupper( trim( $_POST['code_' . $shortcode_id] ) );
		
/* get matching code record from database to check if code is valid */
$wpdb->show_errors();
$code = $wpdb->get_row(
	$wpdb->prepare(
	    "SELECT ID, `release` FROM " . dc_tbl_codes() . " WHERE CONCAT(code_prefix, code_suffix) = %s", array( $post_code )
	)
);
		
if ( $code->ID ) {
	
	/* get release details */
	$release = $wpdb->get_row( "SELECT * FROM " . dc_tbl_releases() . " WHERE ID = " . $code->release );
			
	/* get number of downloads with this code */
	$downloads = $wpdb->get_row(
		$wpdb->prepare(
		    "SELECT COUNT(*) AS downloads FROM " . dc_tbl_downloads() . " WHERE code=(SELECT ID FROM " . dc_tbl_codes() . " WHERE CONCAT(code_prefix, code_suffix) = %s )",
		    array( $post_code )
		)
	);
			
	/* check if maximum of allowed downloads is reached */
	if ( $downloads->downloads < $release->allowed_downloads ) {
		
		/* set temporary download lease ID */
		$lease_ID = md5( 'wp-dl-hash' . $code->ID );
		
		/* generate html code for download form */
		$sku = $release->title;
		$product_ID = get_product_id_by_sku( $sku );
		$artists = array();
		$data = get_field( 'product-artists', $product_ID );
		foreach ( $data as $d ) {
			$artists[] = get_the_title( $d->ID );
		}
		$artist = implode( " & ", $artists );
		$title = get_release_attribute_value( $product_ID, 'release-title' );
		$url = get_stylesheet_directory_uri() . "/download/download.php";;
		
		$return[ 'download_form' ] = "<form id=\"ac-download-form\" name=\"ac-download-form\" action=\"" . $url . "\" method=\"GET\"> \n" . 
			"<input type=\"hidden\" name=\"lease\" value=\"" . $lease_ID . "\" /> \n" . 
			"<div class='title'>" . $sku . " | " . $artist . " - " . $title . "</div> \n" . 
			get_the_post_thumbnail( $product_ID, "download-thumb" ) . "\n" . 
			"<a id=\"ac-download-submit\" class=\"submit\" href=\"javascript:void(0)\" onclick=\"dlFormSubmit()\"><span>Download</span></a>" . 
			"</form>";
		
	} else {
		
		$return[ 'error_message' ] = "Sorry, that code has already been used.";
		
	}

} else {
	
	$return[ 'error_message' ] = "Sorry, that code doesn't exist.";
	
}
	
echo json_encode( $return );


/* get current IP */
// $IP = $_SERVER['REMOTE_ADDR'];

	
/*
	$wpdb->insert(
		dc_tbl_downloads(),
		array( 'code' => $post_code, 'IP' => $IP),
		array( '%d', '%s')
	);
*/
?>