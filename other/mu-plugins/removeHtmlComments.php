<?php

add_action( 'wp_loaded', 'removeHtmlComments' );

/**
 * !ngg_resource - can not be deleted, otherwise the plugin nextgen gallery will not work
 *
 * @param string $data
 *
 * @return mixed
 */
function removeHtmlComments( $data ) {
	//CLRF-166 issue fix bug with noindex (\s?\/?noindex)
	return preg_replace( '#<!--(?!<!|\s?ngg_resource|\s?\/?noindex)[^\[>].*?-->#s', '', $data );
}