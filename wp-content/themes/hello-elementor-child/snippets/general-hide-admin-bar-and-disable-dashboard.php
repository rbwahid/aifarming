<?php
add_action('wp', 'wpdocs_maybe_hide_admin_bar');
function wpdocs_maybe_hide_admin_bar()
{
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $currentUserRole = $user->roles[0];
        $userRoleArr = array('administrator', 'editor', 'author');

        if (!in_array($currentUserRole, $userRoleArr)) {
            show_admin_bar(false);
        }
    }
}
add_action('admin_init', 'disable_dashboard');
function disable_dashboard()
{
    global $sklentr_variable;
    $user = wp_get_current_user();
    if (!is_user_logged_in()) {
        return null;
    }

    $currentUserRole = $user->roles[0];
    $userRoleArr = array('administrator', 'editor', 'author');
    if (is_admin() && !in_array($currentUserRole, $userRoleArr) && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect($sklentr_variable['my_account_url']);
        exit;
    }
}
