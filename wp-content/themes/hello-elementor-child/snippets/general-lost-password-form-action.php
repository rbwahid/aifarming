<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_user_lp', 10, 2);
function general_elementor_form_user_lp($record, $ajax_handler)
{
    global $sklentr_variable;
    $errors    = new WP_Error();
    $form_name = $record->get_form_settings('form_id');

    if ('lostpassword_form' !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $email = $allFields["email"]['value'];
    $getPasswordError = '';
    if (empty($email)) {
        $getPasswordError = '<strong>Error! </strong>Enter a e-mail address.';
    } else if (!is_email($email)) {
        $getPasswordError = '<strong>Error! </strong>Invalid e-mail address.';
    } else if (!email_exists($email)) {
        $getPasswordError = '<strong>Error! </strong>There is no user registered with that email address.';
    } else {
        $errors = retrieve_password($email);
    }

    if ($getPasswordError) {
        $ajax_handler->add_error_message($getPasswordError);
        $ajax_handler->is_success = false;
        return;
    }
    if (is_wp_error($errors)) {
        $ajax_handler->add_error_message($errors);
        $ajax_handler->is_success = false;
        return;
    }
}
function wpdocs_retrieve_password_message($message, $key, $user_login)
{
    $site_name  = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $reset_link = network_site_url("/reset-password?key=$key&login=" . rawurlencode($user_login), 'login');

    // Create new message
    $message = __('Someone has requested a password reset for the following account: ' . $user_login, 'text_domain') . "\n";
    $message .= sprintf(__('Site Name: %s'), network_home_url('/')) . "\n";
    $message .= sprintf(__('Username: %s', 'text_domain'), $user_login) . "\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'text_domain') . "\n";
    $message .= __('To reset your password, visit the following address:', 'text_domain') . "\n";
    $message .= $reset_link . "\n";

    return $message;
}

add_filter('retrieve_password_message', 'wpdocs_retrieve_password_message', 20, 3);
