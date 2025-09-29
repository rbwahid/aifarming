<?php
add_action('template_redirect', 'my_plant_auth_check');
function my_plant_auth_check()
{
    global $post, $sklentr_variable;
    if ($post->post_type == 'my-plant') {
        if (!is_user_logged_in()) {
            wp_redirect($sklentr_variable['plant_url']);
        } else {
            $current_user = wp_get_current_user();
            $postUser = get_field('user');
            if ($current_user->ID != $postUser) {
                wp_redirect($sklentr_variable['plant_url']);
            }
        }
    } else if ($post->post_type == 'plant') {
        if (!is_user_logged_in()) {
            //wp_redirect($sklentr_variable['login_url']);
        }
    } else if ($post->ID == 2771) {
        if (!is_user_logged_in()) {
            wp_redirect($sklentr_variable['login_url']);
        } else {
            if (isset($_GET['path']) && isset($_GET['token'])) {
                $path = $_GET['path'];
                $emailmd5 = $_GET['token'];
                $user = wp_get_current_user();
                $userID = get_current_user_id();
                $plantID = url_to_postid($path);
                if (md5($user->user_email) != $emailmd5 || !$plantID) {
                    wp_redirect($sklentr_variable['plant_url']);
                }
            } else if (!isset($_GET['elementor-preview'])) {
                wp_redirect($sklentr_variable['plant_url']);
            }
        }
    }
}
