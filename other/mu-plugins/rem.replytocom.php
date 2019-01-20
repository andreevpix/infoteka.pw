<?php
/**
* Remove replytocom
*/
add_action('template_redirect', 'removeReplytocomRedirect', 1);
add_filter('comment_reply_link', 'removeReplytocomLink');

function removeReplytocomRedirect() {
    global $post;

    if( !empty($post) && isset($_GET['replytocom']) && is_singular() ) {
        $post_url = get_permalink($post->ID);
        $comment_id = sanitize_text_field($_GET['replytocom']);
        $query_string = remove_query_arg('replytocom', sanitize_text_field($_SERVER['QUERY_STRING']));

        if( !empty($query_string) ) {
            $post_url .= '?' . $query_string;
        }
        $post_url .= '#comment-' . $comment_id;

        wp_safe_redirect($post_url, 301);
        die();
    }

    return false;
}

function removeReplytocomLink($link) {
    return preg_replace('`href=(["\'])(?:.*(?:\?|&|&#038;)replytocom=(\d+)#respond)`', 'href=$1#comment-$2', $link);
}