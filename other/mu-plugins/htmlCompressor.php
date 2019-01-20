<?php
add_action( 'wp_loaded', 'htmlCompressor' );
function htmlCompressor( $content ) {
	$old_content = $content;
	
	if ( get_option( 'remove_xfn_link' ) ) {
		$content = preg_replace( '/<link[^>]+href=(?:\'|")https?:\/\/gmpg.org\/xfn\/11(?:\'|")(?:[^>]+)?>/', '', $content );
		
		if ( empty( $content ) ) {
			$content = $old_content;
		}
	}
	
	return $content;
}