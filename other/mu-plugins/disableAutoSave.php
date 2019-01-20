<?php
add_action('wp_print_scripts', 'disableAutoSave');
function disableAutoSave() {
	wp_deregister_script('autosave');
}