<?php
/**
 * Remove wp version from any enqueued scripts
 *
 * @param string $target_url
 *
 * @return string
 */
add_filter( 'style_loader_src', 'hideWordpressVersionInScript', 9999, 2 );
add_filter( 'script_loader_src', 'hideWordpressVersionInScript', 9999, 2 );
add_filter('the_generator', 'wp_remove_version');

function wp_remove_version() {
	return '';
}
function hideWordpressVersionInScript( $src, $handle ) {
    if ( is_user_logged_in() and get_option( 'disable_remove_style_version_for_auth_users', false ) ) {
        return $src;
    }
    $filename_arr      = explode( '?', basename( $src ) );
    $exclude_file_list = get_option( 'remove_version_exclude', '' );
    $exclude_files_arr = array_map( 'trim', explode( PHP_EOL, $exclude_file_list ) );
    
    if ( strpos( $src, 'ver=' ) && ! in_array( str_replace( '?' . $filename_arr[1], '', $src ), $exclude_files_arr, true ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    
    return $src;
}