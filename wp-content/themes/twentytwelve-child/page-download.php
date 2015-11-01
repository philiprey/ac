<?php
/**
 * Download page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
$url = "download/get-release.php";
?>

	<div id="primary" class="site-content download-content">
			
		<form id="ac-download-form" name="ac-download-form" action="" method="GET">
			<div class="text">
				<p>You're at the right place to download you record.</p>
				<p>Please insert -below- the code written on your download card and click on "<b>Go</b>". Easy, right ?</p> 
				<p>If you have any problem, feel free to contact us at <a href="mailto:atelierciseaux@gmail.com">atelierciseaux@gmail.com</a></p>
			</div>
			<input class="ac-download-code" id="ac-download-code" type="text" name="code" placeholder="Your code" />
			<a id="ac-download-submit" class="submit" href="javascript:void(0)" onclick="dlFormSubmit()">Go</a>
		</form>
		<script>
		$j( '#ac-download-form' ).validate( {
			rules: {
				code: { required: true }
    		}
		} );
		</script>
		<div class="ac-dl-messages"></div>
	</div>

<?php get_footer(); ?>