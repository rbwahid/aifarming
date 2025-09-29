<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_plant_onboarding', 10, 2);
function general_elementor_form_plant_onboarding($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('plant_name', 'plant_age');
    $form_name = $record->get_form_settings('form_id');
    if ('plant_onboarding' !== strtolower($form_name)) {
        return;
    }

    $user = get_current_user_id();
    $current_user_data = get_userdata($user);
    $user_email = $current_user_data->user_email;
    $user_name = $current_user_data->first_name;
    $permalink = get_permalink();

    $allFields     = $record->get('fields');

    $plantID     = $allFields['plant_id']['value'];
    $plant_secret_id     = $allFields['plant_secret_id']['value'];
    $last_repotted =  $allFields['last_repotted']['value'];
    $planted_ground =  $allFields['planted_ground']['value'];
    $plant_name =  $allFields['plant_name']['value'];
    $plant_day = ($allFields['plant_age']['value']) ? $allFields['plant_age']['value'] : 0;
    $days_to_germination = get_field('days_to_germination', $plantID);
    if ($days_to_germination && $plant_day) {
        $plant_day = $plant_day + $days_to_germination;
    }
    if (md5($plantID) !== $plant_secret_id) {
        $ajax_handler->add_error_message("Something wrong! Please Refresh the page & Try Again.");
        $ajax_handler->is_success = false;
        return;
    }

    if (!is_user_logged_in()) {
        $ajax_handler->add_error_message("Please Login first!");
        $ajax_handler->is_success = false;
        return;
    }

    $userID = get_current_user_id();
    $firstName = get_user_meta($userID, 'first_name', true);
    $postType = 'my-plant';
    if ($plant_day) {
        $startingDate = date('Y-m-d H:i:s', strtotime('-' . $plant_day . ' days'));
    } else {
        $startingDate = date('Y-m-d H:i:s');
    }
    $post_data = get_post($plantID);
    if ($post_data) {

        $metaArray = array('user' => $userID, 'plant' => $plantID, 'start_date' => $startingDate);
        $repeaterfield = get_field('field_67276b7d82097', $plantID); //ai_information; post=3316
        $repeatedMeta = array();
        $initialStage = 1;
        $day_0 = 0;
        foreach ($repeaterfield as $field) {
            $repeatedMetaArr = array();
            if ($field['milestone_stage'] == $initialStage) {
                foreach ($field as $key => $value) {

                    if ($key == 'information_day') {
                        if ($value == 0) {
                            $repeatedMetaArr['task_complete'] = true;
                            $day_0 = 1;
                        } else {
                            $repeatedMetaArr['task_complete'] = false;
                        }
                        $value = date('Y-m-d H:i:s', strtotime('+' . $value . ' days', strtotime($startingDate)));
                    }
                    if (!is_array($value)) {
                        $value = str_replace('[first_name]', $firstName, $value);
                        $value = str_replace('[first-name]', $firstName, $value);
                    }

                    $repeatedMetaArr[$key] = $value;
                }
                $repeatedMeta[] = $repeatedMetaArr;
            }
        }
        $numberOfPlants = check_plant($userID, $plantID) + 1;
        $my_post = array(
            'post_title'    => ($plant_name) ? $plant_name : $post_data->post_title,
            'post_name'     => $userID . "_" . $post_data->post_name . "_" . $numberOfPlants,
            'post_status'   => 'publish',
            'post_type'     => $postType,
            'post_author'   => $userID,
            'meta_input'   => $metaArray,
        );
        // Insert my-plant post into the database
        $insertID = wp_insert_post($my_post);

        //print_r($repeatedMeta);
        update_field('field_672780b348ddd', $repeatedMeta, $insertID); //My plant AI information update
        update_field('field_672b5f289bd9a', $initialStage, $insertID); //Current Milestone Stage update
        if ($day_0) {

            $send_email_date = "";
            $notification = $repeatedMeta[0];
            if ($notification['send_email']) {
                //send Email 
                $user = get_user_by('id', $userID);
                wp_mail($user->user_email, $notification['email_subject'], $notification['email_body']);
                $send_email_date = date('Y-m-d H:i:s');
            }

            plant_history_create_v2($insertID, $notification, $send_email_date);
        }
        foreach ($record->get('fields') as $key => $field) {
            if ($field['type'] != 'step') {
                if (!in_array($key, $ignore_fields)) {
                    if ($field['type'] == 'upload') {
                        if ($field['value']) {
                            $media_id = upload_file_to_media_library($field['raw_value']);
                            update_field($key, $media_id, $insertID);
                        }
                    } else {
                        update_post_meta($insertID, $key, $field['raw_value']);
                    }
                }
            }
        }
    }
}
