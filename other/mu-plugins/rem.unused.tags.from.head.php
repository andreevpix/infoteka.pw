<?php
/**
 * Remove unused tags from head
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );

add_filter( 'avf_profile_head_tag', 'removeXfnLink' );
function removeXfnLink() {
	return false;
}
?>