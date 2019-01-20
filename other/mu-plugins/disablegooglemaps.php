<?php
/** ======================================================================== */
//                        Disable google maps
/** ======================================================================== */

/**
 * @param string $html
 * @return mixed
 */
add_action("wp_loaded", 'disableGoogleMapsObStart');
function disableGoogleMapsObStart($html)
{
	global $post;

	$exclude_ids = array();
	if( $post && !in_array($post->ID, $exclude_ids, true) ) {
		$html = preg_replace('/<script[^<>]*\/\/maps.(googleapis|google|gstatic).com\/[^<>]*><\/script>/i', '', $html);
	}

	return $html;
}

/** ======================================================================== */
//                         End disable google maps
/** ======================================================================== */