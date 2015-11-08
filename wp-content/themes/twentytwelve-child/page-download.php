<?php
/**
 * Download page
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

get_header();
$url = get_stylesheet_directory_uri() . "/download/check-download-code.php";
?>

	<div id="primary" class="site-content download-content">
			
		<form id="ac-download-check-form" name="ac-download-check-form" action="<?php echo $url; ?>" method="GET">
			<div class="text">
				<p>You're at the right place to download your record.</p>
				<p>Please insert -below- the code written on your download card and click on "<b>Go</b>". Easy, right ?</p> 
				<p>If you have any problem, feel free to contact us at <a href="mailto:atelierciseaux@gmail.com">atelierciseaux@gmail.com</a></p>
			</div>
			<input class="ac-download-code" id="ac-download-code" type="text" name="code" placeholder="Your code" />
			<a id="ac-download-check-submit" class="submit" href="javascript:void(0)" onclick="dlCheckFormSubmit()"><span>Go</span></a>
			<p class="ac-dl-error-message"></p>
		</form>
		<script>
		$j( '#ac-download-check-form' ).validate( {
			rules: {
				code: { required: true }
    		}
		} );
		</script>
		<div id="ac-download-form-container"></div>
	</div>

<?php get_footer(); ?>