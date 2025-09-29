<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_user_login', 10, 2);
function general_elementor_form_user_login($record, $ajax_handler)
{
    global $sklentr_variable;
    $form_name = $record->get_form_settings('form_id');

    if ($sklentr_variable['login_form_id'] !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $username = $allFields["email"]['value'];
    $password = $allFields["password"]['value'];


    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        $ajax_handler->add_error_message("Invalid login credentials.");
        $ajax_handler->is_success = false;
        return;
    }

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    //do_action( 'wp_login', $user->user_login, $user );
    //fetch_weather();
}
function after_login()
{
    global $sklentr_variable;
    $userPageArr = array(3637, 821, 3646);
    if (in_array(get_the_ID(), $userPageArr)) {
        if (!is_user_logged_in()) {
            wp_redirect($sklentr_variable['login_url']);
            die;
        }
    }
    if (get_permalink() == $sklentr_variable['login_url']) {
        if (is_user_logged_in() && !isset($_GET['elementor-preview'])) {
            wp_redirect($sklentr_variable['my_account_url']);
            die;
        }
    }
    if (get_permalink() == $sklentr_variable['my_account_url']) {
        fetch_weather();
    }
    if (is_user_logged_in()) {
        $userId = get_current_user_id();
        $current_step = get_user_meta($userId, 'complete_step', true);
        $final_step = $sklentr_variable['email_verification_step'];
        if ($current_step && $current_step != $final_step) {
            $next_step = $current_step + 1;
            $step = 'registration_stage_' . $next_step . '_page';
            if (get_the_ID() != $sklentr_variable[$step]) {
                wp_redirect(get_permalink($sklentr_variable[$step])); //change this ID with Step page.
                die;
            }
        }
    }
}
add_action('template_redirect', 'after_login');
