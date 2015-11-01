<?php
/**
 * Get release ID for current download code
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */
 
global $wpdb;

$code = trim( $_GET[ 'code' ] );
$release = $wpdb->get_row(
	$wpdb->prepare(
	    "SELECT release FROM " . dc_tbl_releases() . " WHERE code_suffix = %s", array( $code )
	)
);

echo $release;	
?>