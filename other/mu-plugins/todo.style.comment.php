<?php

add_action( 'widgets_init', 'removeRecentCommentsStyle' );

function removeRecentCommentsStyle() {
    global $wp_widget_factory;
    
    $widget_recent_comments = isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ? $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] : null;
    
    if ( ! empty( $widget_recent_comments ) ) {
        remove_action( 'wp_head', array(
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
        ) );
    }
}