<?php
/**
 * Disables the WP REST API for visitors not logged into WordPress.
 */
/*
	Disable REST API link in HTTP headers
	Link: <https://example.com/wp-json/>; rel="https://api.w.org/"
*/
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
/*
	Disable REST API links in HTML <head>
	<link rel='https://api.w.org/' href='https://example.com/wp-json/' />
*/
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
?>