<?php
/**
 * Simple logging for WordPress.
 *
 * @package BJ\Log
 * @author bjornjohansen
 * @version 0.1.0
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
 */
if ( ! function_exists( 'write_log' ) ) {
	/**
	 * Utility function for logging arbitrary variables to the error log.
	 *
	 * Set the constant WP_DEBUG to true and the constant WP_DEBUG_LOG to true to log to wp-content/debug.log.
	 * You can view the log in realtime in your terminal by executing `tail -f debug.log` and Ctrl+C to stop.
	 *
	 * @param mixed $log Whatever to log.
	 */
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_scalar( $log ) ) {
				error_log( $log );
			} else {
				error_log( print_r( $log, true ) );
			}
		}
	}
}