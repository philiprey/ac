<?php
/**
 * Download page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
?>

	<div id="primary" class="site-content download-content">
			
		<form id="ac-download-form" name="ac-download-form" action="??????????" method="GET">
			<div class="text">Enter your download code here yeah:</div>
			<input class="ac-download-code" id="ac-download-code" type="text" name="code" placeholder="Code" />
			<a id="ac-download-submit" class="submit" href="javascript:void(0)" onclick="dlFormSubmit()">Go</a>
		</form>
		
	</div>

<?php get_footer(); ?>