<?php
/**
 * Disable dashicons for all but the auth user
 */
add_action( 'wp_print_styles', 'disableDashicons', - 1 );
function disableDashicons() {
	if ( ! is_admin_bar_showing() && ! is_customize_preview() ) {
		wp_deregister_style( 'dashicons' );
	}
}
