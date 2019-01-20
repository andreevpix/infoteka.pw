<?php
/**
 * Disable the emoji's
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param  array $urls URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 *
 * @return array Difference betwen the two arrays.
 */
add_filter( 'wp_resource_hints', 'disableEmojisRemoveDnsPrefetch', 10, 2 );
function disableEmojisRemoveDnsPrefetch( $urls, $relation_type ) {
		
	if ( 'dns-prefetch' == $relation_type ) {
		
		// Strip out any URLs referencing the WordPress.org emoji location
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[ $key ] );
			}
		}
	}
	
	return $urls;
}