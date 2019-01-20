<?php
    /**
    * Add post title in image alt attribute
    *
    * @param $content
    * @return mixed
    */

add_filter('the_content', 'contentImageAutoAlt');
add_filter('wp_get_attachment_image_attributes', 'changeAttachementImageAttributes', 20, 2);

function contentImageAutoAlt($content) {
    global $post;

    if( empty($post) ) {
        return $content;
    }

    $old_content = $content;

    preg_match_all('/<img[^>]+>/', $content, $images);

    if( !is_null($images) ) {
        foreach($images[0] as $index => $value) {
            if( !preg_match('/alt=/', $value) ) {
                $new_img = str_replace('<img', '<img alt="' . esc_attr($post->post_title) . '"', $images[0][$index]);
                $content = str_replace($images[0][$index], $new_img, $content);
            } else if( preg_match('/alt=[\s"\']{2,3}/', $value) ) {
                $new_img = preg_replace('/alt=[\s"\']{2,3}/', 'alt="' . esc_attr($post->post_title) . '"', $images[0][$index]);
                $content = str_replace($images[0][$index], $new_img, $content);
            }
        }
    }

    if( empty($content) ) {
        return $old_content;
    }

    return $content;
}
function changeAttachementImageAttributes($attr, $attachment)
{
    // Get post parent
    $parent = get_post_field('post_parent', $attachment);

    // Get post type to check if it's product
    //$type = get_post_field('post_type', $parent);

    /*if( $type != 'product' ) {
        return $attr;
    }*/

    /// Get title
    $title = get_post_field('post_title', $parent);
    if( '' === $attr['alt'] ) {
        $attr['alt'] = $title;
    }
    $attr['title'] = $title;

    return $attr;
}