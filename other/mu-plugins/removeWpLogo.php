<?php

add_action('wp_before_admin_bar_render', 'removeWpLogo');
function removeWpLogo() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}