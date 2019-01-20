<?php
add_filter('robots_txt', 'rightRobotsTxt', 9999);

function rightRobotsTxt($output) {
    if( get_option('robots_txt_text') ) {
        return get_option('robots_txt_text');
    }

    return WCL_Helper::getRightRobotTxt();
}