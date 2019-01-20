<?php
/**
 * Disable feeds
 */
add_action( 'wp_loaded', 'removeFeedLinks' );
add_action( 'template_redirect', 'filterFeeds', 1 );

function removeFeedLinks() {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}

function filterFeeds() {
	if ( ! is_feed() || is_404() ) {
		return;
	}
	
	$this->disabled_feed_behaviour();
}

function disabled_feed_behaviour() {
	global $wp_rewrite, $wp_query;
	
	if ( get_option( 'disabled_feed_behaviour', 'redirect_301' ) == 'redirect_404' ) {
		$wp_query->is_feed = false;
		$wp_query->set_404();
		status_header( 404 );
		// Override the xml+rss header set by WP in send_headers
		header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
	} else {
		if ( isset( $_GET['feed'] ) ) {
			wp_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
			exit;
		}
		
		if ( 'old' !== get_query_var( 'feed' ) ) {    // WP redirects these anyway, and removing the query var will confuse it thoroughly
			set_query_var( 'feed', '' );
		}
		
		redirect_canonical();    // Let WP figure out the appropriate redirect URL.
		
		// Still here? redirect_canonical failed to redirect, probably because of a filter. Try the hard way.
		$struct = ( ! is_singular() && is_comment_feed() ) ? $wp_rewrite->get_comment_feed_permastruct() : $wp_rewrite->get_feed_permastruct();
		
		$struct        = preg_quote( $struct, '#' );
		$struct        = str_replace( '%feed%', '(\w+)?', $struct );
		$struct        = preg_replace( '#/+#', '/', $struct );
		$requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		$new_url = preg_replace( '#' . $struct . '/?$#', '', $requested_url );
		
		if ( $new_url !== $requested_url ) {
			wp_redirect( $new_url, 301 );
			exit;
		}
	}
}

/**
 * BBPress feed detection sourced from bbp_request_feed_trap() in BBPress Core.
 *
 * @param  array $query_vars
 *
 * @return array
 */
add_filter( 'bbp_request', 'filterBbpFeeds', 9 );
function filterBbpFeeds( $query_vars ) {
	// Looking at a feed
	if ( isset( $query_vars['feed'] ) ) {
		
		// Forum/Topic/Reply Feed
		if ( isset( $query_vars['post_type'] ) ) {
			
			// Matched post type
			$post_type  = false;
			$post_types = array();
			
			if ( function_exists( 'bbp_get_forum_post_type' ) && function_exists( 'bbp_get_topic_post_type' ) && function_exists( 'bbp_get_reply_post_type' ) ) // Post types to check
			{
				$post_types = array(
					bbp_get_forum_post_type(),
					bbp_get_topic_post_type(),
					bbp_get_reply_post_type(),
				);
			}
			
			// Cast query vars as array outside of foreach loop
			$qv_array = (array) $query_vars['post_type'];
			
			// Check if this query is for a bbPress post type
			foreach ( $post_types as $bbp_pt ) {
				if ( in_array( $bbp_pt, $qv_array, true ) ) {
					$post_type = $bbp_pt;
					break;
				}
			}
			
			// Looking at a bbPress post type
			if ( ! empty( $post_type ) ) {
				$this->disabled_feed_behaviour();
			}
		}
	}
	
	// No feed so continue on
	return $query_vars;
}
?>