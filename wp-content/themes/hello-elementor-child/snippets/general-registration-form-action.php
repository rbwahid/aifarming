<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_create_new_user', 10, 2);
function general_elementor_form_create_new_user($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname', 'password', 'username', 'email');
    $form_name = $record->get_form_settings('form_id');
    if ($sklentr_variable['registration_form_id'] !== $form_name) {
        return;
    }
    $form_data     = $record->get_formatted_data();
    $allFields     = $record->get('fields');
    $password     = $allFields['password']['value'];
    $email        = $allFields['email']['value'];
    $user = wp_create_user($email, $password, $email);

    if (is_wp_error($user)) {
        $ajax_handler->add_error_message("Failed to create new user: " . $user->get_error_message());
        $ajax_handler->is_success = false;
        return;
    }
    $first_name = $allFields["fname"]['value'];
    $last_name = $allFields["lname"]['value'];
    wp_update_user(array("ID" => $user, "first_name" => $first_name, "last_name" => $last_name));

    foreach ($record->get('fields') as $key => $field) {
        if (!in_array($key, $ignore_fields)) {
            add_user_meta($user, $key, $field['raw_value']);
        }
    }
}
