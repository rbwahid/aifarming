<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_email_verification', 10, 2);
function general_elementor_form_email_verification($record, $ajax_handler)
{
    global $sklentr_variable;
    $form_name = $record->get_form_settings('form_id');

    if ('email_verification' !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $email = $allFields["email"]['value'];

    $otp = rand(1000, 9999);
    if (!email_exists($email)) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'user_otp';
        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE email='$email' AND status=1", ARRAY_A);
        if (!empty($results)) {
            $ajax_handler->add_success_message("Please wait...");

            $redirect_to = get_permalink($sklentr_variable['registration_stage_1_page']) . "?email=" . $email;
            $ajax_handler->add_response_data('redirect_url', $redirect_to);
            return;
        }
        $table_name = $wpdb->prefix . 'user_otp';

        $wpdb->update(
            $table_name,
            array('status' => 2),
            array('email' => $email),
            array('%d'),
            array('%d')
        );
        $wpdb->insert(
            $table_name,
            array(
                'email' => $email,
                'otp'   => $otp,
                'status' => 0,
            ),
            array(
                '%s',
                '%s',
                '%d'
            )
        );
    } else {
        $ajax_handler->add_success_message("Please wait...");
        $redirect_to = $sklentr_variable['login_url'];
        $ajax_handler->add_response_data('redirect_url', $redirect_to);
        return;
    }
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $subject = get_bloginfo('name') . " | Verify your email";
    $message = "<br/>Thank you for starting your registration process! To complete your registration, please use the following One-Time Password (OTP):<br/><br/><b>Your OTP: {$otp}</b><br/><br/>If you did not initiate this request, please ignore this email.<br/><br/>If you need any assistance, feel free to reach out to our support team.";

    wp_mail($email, $subject, $message, $headers);

    $redirect_to = get_permalink() . "?email=" . $email;
    $ajax_handler->add_response_data('redirect_url', $redirect_to);
}

add_action('elementor_pro/forms/new_record',  'general_elementor_form_check_otp', 10, 2);
function general_elementor_form_check_otp($record, $ajax_handler)
{
    global $sklentr_variable;
    $form_name = $record->get_form_settings('form_id');

    if ('check_otp' !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $otp = $allFields["otp"]['value'];
    $email = $allFields["email"]['value'];
    if ($email) {
        if (!email_exists($email)) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'user_otp';

            $results = $wpdb->get_results("SELECT * FROM $table_name WHERE email='$email' AND status=0", ARRAY_A);

            if (!empty($results)) {
                $row = $results[0];
                if ($row['otp'] == $otp) {
                    $wpdb->update(
                        $table_name,
                        array('status' => 1),
                        array('id' => $row['id']),
                        array('%d'),
                        array('%d')
                    );
                    $page_id  = $sklentr_variable['registration_stage_1_page'];
                    $redirect_to = get_permalink($page_id) . "?email=" . $email;
                    $ajax_handler->add_response_data('redirect_url', $redirect_to);
                    return;
                } else {
                    $ajax_handler->add_error_message("Invalid OTP. Please try again.");
                    $ajax_handler->is_success = false;
                    return;
                }
            } else {
                $ajax_handler->add_error_message("Something Wrong! Please try again with correct email address.");
                $ajax_handler->is_success = false;
            }
        } else {
            $ajax_handler->add_error_message("That E-mail is registered to another user.");
            $ajax_handler->is_success = false;
        }
    }
    $redirect_to = get_permalink();
    $ajax_handler->add_response_data('redirect_url', $redirect_to);
    return;
}

function get_user_email()
{
    return (isset($_GET['email'])) ? $_GET['email'] : '';
}
add_shortcode('get_user_email', 'get_user_email');

function is_already_email()
{
    return (isset($_GET['email'])) ? 1 : 0;
}
add_shortcode('is_already_email', 'is_already_email');
function custom_redirects()
{
    global $sklentr_variable;
    if (get_the_ID() == $sklentr_variable['email_verification_page_id'] && is_user_logged_in() && !isset($_GET['elementor-preview'])) {
        wp_redirect($sklentr_variable['my_account_url']);
        die;
    }
}
add_action('template_redirect', 'custom_redirects');
