<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_user_cp', 10, 2);
function general_elementor_form_user_cp($record, $ajax_handler)
{
    global $sklentr_variable;
    $form_name = $record->get_form_settings('form_id');

    if ('change_password' !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $u_opwd = $allFields["user_opassword"]['value'];
    $u_pwd = trim($allFields["user_password"]['value']);
    $u_cpwd = trim($allFields["user_cpassword"]['value']);

    $user = wp_get_current_user();
    $changePasswordError = '';

    if ($u_opwd == '' || $u_pwd == '' || $u_cpwd == '') {
        $changePasswordError .= '<strong>ERROR: </strong> Enter Password.,';
    }

    if (!wp_check_password($u_opwd, $user->data->user_pass, $user->ID)) {
        $changePasswordError .= '<strong>ERROR: </strong> Old Password wrong.,';
    }

    if ($u_pwd != $u_cpwd) {
        $changePasswordError .= '<strong>ERROR: </strong> Password are not matching.,';
    }

    if (strlen($u_pwd) < 6) {
        $changePasswordError .= '<strong>ERROR: </strong> Use minimum 6 character in password.,';
    }
    $changePasswordError = trim($changePasswordError, ',');
    $changePasswordError = str_replace(",", "<br/>", $changePasswordError);
    if ($changePasswordError) {
        $ajax_handler->add_error_message($changePasswordError);
        $ajax_handler->is_success = false;
        return;
    } else {
        wp_set_password($u_pwd, $user->ID);
        do_action('wp_login', $user->user_login, $user);
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
    }
}
