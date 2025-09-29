<?php
add_action('elementor_pro/forms/validation',  'general_elementor_form_create_an_account', 10, 2);
function general_elementor_form_create_an_account($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname', 'password', 'username', 'email');
    $form_name = $record->get_form_settings('form_id');
    if ("create_an_account" !== $form_name) {
        return;
    }
    $form_data     = $record->get_formatted_data();
    $allFields     = $record->get('fields');

    $password     = $allFields['password']['value'];
    $email        = $allFields['email']['value'];
    $firstname  = $allFields['fname']['value'];
    $lastname  = $allFields['lname']['value'];

    if ($email) {
        if (!email_exists($email)) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'user_otp';

            $results = $wpdb->get_results("SELECT * FROM $table_name WHERE email='$email' AND status=1", ARRAY_A);

            if (!empty($results)) {
                $row = $results[0];

                $user = wp_create_user($email, $password, $email);
                if (is_wp_error($user)) {
                    $ajax_handler->add_error_message($user->get_error_message());
                    $ajax_handler->is_success = false;
                    return;
                }
                $first_name = $allFields["fname"]['value'];
                $last_name = $allFields["lname"]['value'];
                wp_update_user(array("ID" => $user, "first_name" => $firstname, "last_name" => $lastname));

                update_user_meta($user, 'complete_step', 1);
                return;
            }
        } else {
            $redirect_to = get_permalink($sklentr_variable['login_url']);
            $ajax_handler->add_response_data('redirect_url', $redirect_to);
        }
    }
    $redirect_to = get_permalink($sklentr_variable['email_verification_page_id']);
    $ajax_handler->add_response_data('redirect_url', $redirect_to);
    return;
}

function custom_redirects_2()
{
    global $sklentr_variable;
    if (get_the_ID() == $sklentr_variable['registration_stage_1_page']) {
        $email = isset($_GET['email']) ? $_GET['email'] : "";
        if (!$email) {
            wp_redirect(get_permalink($sklentr_variable['email_verification_page_id']));
            die;
        }
        global $wpdb;
        if (email_exists($email)) {
            $user = get_user_by('email', $email);
            $step = get_user_meta($user->ID, 'complete_step', true);
            wp_redirect($sklentr_variable['login_url']); //change this ID with Step page.
        }
        $table_name = $wpdb->prefix . 'user_otp';
        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE email='$email' AND status=1", ARRAY_A);
        if (empty($results)) {
            wp_redirect(get_permalink($sklentr_variable['email_verification_page_id']));
            die;
        }
    }
}
add_action('template_redirect', 'custom_redirects_2');
