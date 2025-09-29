<?php
add_filter('gform_pre_render_1', 'populate_checkbox_choices');
add_filter('gform_pre_validation_1', 'populate_checkbox_choices');
add_filter('gform_pre_submission_filter_1', 'populate_checkbox_choices');
add_filter('gform_admin_pre_render_1', 'populate_checkbox_choices');

function populate_checkbox_choices($form)
{
    foreach ($form['fields'] as $field) {
        if ($field['type'] == 'hidden' && $field['inputName'] == 'plant_id') {
            $pageURL = $_GET['path'];
            $plant_id = url_to_postid($pageURL);
            $field['defaultValue'] =  $plant_id;
        }
        if ($field->id == 33) {
            $choices = [];
            $indoorLocations = get_field('indoor_locations', 'user_' . get_current_user_id());
            foreach ($indoorLocations as $data) {
                $choices[] = ['text' => $data, 'value' => $data];
            }
            $field->choices = $choices;
        } else if ($field->id == 39) {
            $choices = [];
            $outdoorLocations = get_field('outdoor_locations', 'user_' . get_current_user_id());
            foreach ($outdoorLocations as $data) {
                $choices[] = ['text' => $data, 'value' => $data];
            }
            $field->choices = $choices;
        }
    }

    return $form;
}
add_action('gform_advancedpostcreation_post_after_creation_1', 'after_post_creation2', 5, 4);
function after_post_creation2($post_id, $feed, $entry, $form)
{

    $userID = get_current_user_id();
    $plantID = rgar($entry, 41);
    $plant_day = 0;


    update_field('plant', rgar($entry, 41), $post_id);
    update_field('indoor_or_outdoor', rgar($entry, 38), $post_id);
    update_field('user', get_current_user_id(), $post_id);
    if (rgar($entry, 38) == 'Indoor') {
        update_field('plant_site', rgar($entry, 33), $post_id);
        update_field('pot_or_ground', rgar($entry, 40), $post_id);
    } else {
        update_field('plant_site', rgar($entry, 39), $post_id);
        update_field('pot_or_ground', rgar($entry, 9), $post_id);
    }

    //text convert
    $firstName = get_user_meta($userID, 'first_name', true);

    if ($plant_day) {
        $startingDate = date('Y-m-d H:i:s', strtotime('-' . $plant_day . ' days'));
    } else {
        $startingDate = date('Y-m-d H:i:s');
    }
    $post_data = get_post($plantID);
    if ($post_data) {

        update_field('start_date', $startingDate, $post_id);
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
    }
    update_field('field_672780b348ddd', $repeatedMeta, $post_id); //My plant AI information update
    update_field('field_672b5f289bd9a', $initialStage, $post_id); //Current Milestone Stage update
    update_field('field_6734748d9f5ca', 0, $post_id);
    if ($day_0) {

        $send_email_date = "";
        $notification = $repeatedMeta[0];
        if ($notification['send_email']) {
            //send Email 
            $user = get_user_by('id', $userID);
            wp_mail($user->user_email, $notification['email_subject'], $notification['email_body']);
            $send_email_date = date('Y-m-d H:i:s');
        }

        plant_history_create_v2($post_id, $notification, $send_email_date);
    }
    $redirect_url = get_permalink($post_id);
    add_filter('gform_confirmation_1', function ($confirmation) use ($redirect_url) {
        return array('redirect' => $redirect_url);
    });
}
