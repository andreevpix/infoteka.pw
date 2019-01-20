<?php
// Removes the server responses a reference to the xmlrpc file.
add_filter('template_redirect', 'removeXmlRpcPingbackHeaders');
add_filter('wp_headers', 'disableXmlRpcPingback');

// Remove <link rel="pingback" href>
add_action('template_redirect', 'removeXmlRpcTagBufferStart', -1);
add_action('get_header', 'removeXmlRpcTagBufferStart');
add_action('wp_head', 'removeXmlRpcTagBufferStart', 999);

// Remove RSD link from head
remove_action('wp_head', 'rsd_link');

// Disable xmlrcp/pingback
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pre_update_option_enable_xmlrpc', '__return_false');
add_filter('pre_option_enable_xmlrpc', '__return_zero');
add_filter('pings_open', '__return_false');

// Force to uncheck pingbck and trackback options
add_filter('pre_option_default_ping_status', '__return_zero');
add_filter('pre_option_default_pingback_flag', '__return_zero');

add_filter('xmlrpc_methods', 'removeXmlRpcMethods');
add_action('xmlrpc_call', 'disableXmlRpcCall');

// Hide options on Discussion page
add_action('admin_enqueue_scripts', 'removeXmlRpcHideOptions');
/**
* Just disable pingback.ping functionality while leaving XMLRPC intact?
*
* @param $method
*/
function disableXmlRpcCall($method) {
    if( $method != 'pingback.ping' ) {
        return;
    }
    wp_die('This site does not have pingback.', 'Pingback not Enabled!', array('response' => 403));
}

function removeXmlRpcMethods($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    unset($methods['wp.getUsersBlogs']); // Block brute force discovery of existing users
    unset($methods['system.multicall']);
    unset($methods['system.listMethods']);
    unset($methods['system.getCapabilities']);

    return $methods;
}

/**
* Disable X-Pingback HTTP Header.
*
* @param array $headers
* @return mixed
*/
function disableXmlRpcPingback($headers) {
    unset($headers['X-Pingback']);

    return $headers;
}

/**
* Disable X-Pingback HTTP Header.
*
* @param array $headers
* @return mixed
*/
function removeXmlRpcPingbackHeaders() {
    if( function_exists('header_remove') ) {
        header_remove('X-Pingback');
        header_remove('Server');
    }
}

/**
* Start buffer for remove <link rel="pingback" href>
*/
function removeXmlRpcTagBufferStart() {
    ob_flush();
}

/**
* @param $buffer
* @return mixed
*/
function removeXmlRpcTag($buffer) {
    preg_match_all('/(<link([^>]+)rel=("|\')pingback("|\')([^>]+)?\/?>)/im', $buffer, $founds);

    if( !isset($founds[0]) || count($founds[0]) < 1 ) {
        return $buffer;
    }

    if( count($founds[0]) > 0 ) {
        foreach($founds[0] as $found) {
            if( empty($found) ) {
                continue;
            }

            $buffer = str_replace($found, "", $buffer);
        }
    }

    return $buffer;
}

/**
* Hide Discussion options with CSS
*
* @return null
*/
function removeXmlRpcHideOptions($hook) {
    if( 'options-discussion.php' !== $hook ) {
        return;
    }

    wp_add_inline_style('dashboard', '.form-table td label[for="default_pingback_flag"], .form-table td label[for="default_pingback_flag"] + br, .form-table td label[for="default_ping_status"], .form-table td label[for="default_ping_status"] + br { display: none; }');
}

/**
* Set disabled header for any XML-RPC requests
*/
function xmlRpcSetDisabledHeader() {
    // Return immediately if SCRIPT_FILENAME not set
    if( !isset($_SERVER['SCRIPT_FILENAME']) ) {
        return;
    }

    $file = basename($_SERVER['SCRIPT_FILENAME']);

    // Break only if xmlrpc.php file was requested.
    if( 'xmlrpc.php' !== $file ) {
        return;
    }

    $header = 'HTTP/1.1 403 Forbidden';

    header($header);
    echo $header;
    die();
}