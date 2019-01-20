<?php
/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array $plugins
 *
 * @return   array Difference betwen the two arrays
 */
add_filter( 'tiny_mce_plugins', 'disableEmojisTinymce' );
function disableEmojisTinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	
	return array();
}