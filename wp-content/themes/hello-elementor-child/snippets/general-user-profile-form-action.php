<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_user_profile', 10, 2);
function general_elementor_form_user_profile($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname');
    $form_name = $record->get_form_settings('form_id');

    if ($sklentr_variable['profile_form_id'] !== strtolower($form_name)) {
        return;
    }

    $user = get_current_user_id();

    $allFields     = $record->get('fields');

    $first_name = $allFields["fname"]['value'];
    $last_name = $allFields["lname"]['value'];
    wp_update_user(array("ID" => $user, "first_name" => $first_name, "last_name" => $last_name));

    foreach ($record->get('fields') as $key => $field) {
        if (!in_array($key, $ignore_fields)) {
            //print_r(get_user_meta($user));die;
            update_user_meta($user, $key, $field['raw_value']);
        }
    }
    fetch_weather();
}
