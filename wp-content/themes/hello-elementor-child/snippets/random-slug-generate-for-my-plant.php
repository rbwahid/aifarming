<?php
// Set a random & unique slug for Photos Custom Post Type
add_filter('wp_unique_post_slug', 'custom_unique_post_slug', 10, 4);
function custom_unique_post_slug($slug, $post_ID, $post_status, $post_type)
{
    if ('my-plant' == $post_type) { // change to match your post type
        $post = get_post($post_ID);
        if (empty($post->post_name) || $slug != $post->post_name) {
            $slug = get_current_user_id() . '-' . md5(time());
        }
    }
    return $slug;
}
