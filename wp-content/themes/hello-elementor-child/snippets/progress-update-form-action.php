<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_progress_update', 10, 2);
function general_elementor_form_progress_update($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array();
    $form_name = $record->get_form_settings('form_id');

    if ('progress_update' !== strtolower($form_name)) {
        return;
    }

    $user = get_current_user_id();
    $current_user_data = get_userdata($user);
    $user_email = $current_user_data->user_email;
    $user_name = $current_user_data->first_name;
    $permalink = get_permalink();

    $allFields     = $record->get('fields');

    $photo_url = $allFields["plant_photo"]['raw_value'];
    $attached_id = upload_file_to_media_library($photo_url);
    $images = get_field('plant_picture', get_the_ID());
    if (!$images) {
        $images = array();
    }
    $images[] = $attached_id;
    update_field('plant_picture', $images, get_the_ID());

    $type = 5;
    $answer = array(
        "plant_health" => $allFields["plant_health"]['value'],
        "plant_photo" => $attached_id,
        "comment" => $allFields["comment"]['value']
    );
    plant_history_create(get_the_ID(), $type, $answer);
}
