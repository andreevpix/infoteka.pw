<?php
add_action('wp_enqueue_scripts', 'disableAllGoogleFonts', 999);
function disableAllGoogleFonts()
{
	global $wp_styles;

	//	multiple patterns hook
	$regex = '/fonts\.googleapis\.com\/css\?family/i';

	foreach($wp_styles->registered as $registered) {

		if( preg_match($regex, $registered->src) ) {
			wp_dequeue_style($registered->handle);
		}
	}
}