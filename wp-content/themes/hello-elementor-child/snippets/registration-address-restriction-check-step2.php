<?php
add_action('elementor_pro/forms/validation',  'general_elementor_form_your_address', 10, 2);
function general_elementor_form_your_address($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname', 'password', 'username', 'email');
    $form_name = $record->get_form_settings('form_id');
    if ("your_address" !== $form_name) {
        return;
    }
    $form_data     = $record->get_formatted_data();
    $allFields     = $record->get('fields');

    $user = get_current_user_id();

    foreach ($allFields as $key => $field) {
        if (!in_array($key, $ignore_fields)) {
            add_user_meta($user, $key, $field['raw_value']);
        }
    }
    update_user_meta($user, 'complete_step', 2);
    fetch_weather();
    $redirect_to = get_permalink($sklentr_variable['registration_stage_2_page']);
    $ajax_handler->add_response_data('redirect_url', $redirect_to);
    return;
}

function custom_redirects_your_address()
{
    global $sklentr_variable;
    if (get_the_ID() == $sklentr_variable['registration_stage_2_page'] && !isset($_GET['elementor-preview'])) {
        if (!is_user_logged_in()) {
            wp_redirect($sklentr_variable['login_url']);
        }
        $userId = get_current_user_id();
        $current_step = get_user_meta($userId, 'complete_step', true);
        $final_step = $sklentr_variable['email_verification_step'];
        if ($current_step && $current_step != 1) {
            $next_step = $current_step + 1;
            if ($current_step == $final_step) {
                wp_redirect($sklentr_variable['my_account_url']);
            }
            $step = 'registration_stage_' . $next_step . '_page';
            wp_redirect(get_permalink($sklentr_variable[$step])); //change this ID with Step page.	
        }
    }
}
add_action('template_redirect', 'custom_redirects_your_address');
