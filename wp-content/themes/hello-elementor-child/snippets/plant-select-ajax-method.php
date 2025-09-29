<?php
//check number of same plant
function check_plant($user_id, $plant_id)
{
    if (!$user_id || !$plant_id) {
        return 0;
    }
    $args = array(
        'post_type' => 'my-plant',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'user',
                'value' => $user_id,
                'compare' => '='
            ),
            array(
                'key' => 'plant',
                'value' => $plant_id,
                'compare' => '='
            )

        ),

    );
    $myPlantPosts = new WP_Query($args);

    return $myPlantPosts->found_posts ?? 0;
}
//plant Select ajax function 
function get_data()
{
    global $sklentr_variable;
    if (!is_user_logged_in()) {
        echo 0;
        wp_die();
    }
    $plantID = $_GET['plant'];
    $userID = get_current_user_id();
    $firstName = get_user_meta($userID, 'first_name', true);
    $postType = 'my-plant';

    $post_data = get_post($plantID);
    if ($post_data) {
        $metaArray = array('user' => $userID, 'plant' => $plantID);
        $repeaterfield = get_field('notification_settings', $plantID);
        $repetedMeta = array();
        foreach ($repeaterfield as $field) {
            $repetedMetaArr = array();
            $emailSend = "email_send";
            foreach ($field as $key => $value) {
                if ($key == 'plant_day') {
                    if ($value == 0) {
                        $repetedMetaArr['email_send'] = 1;
                    } else {
                        $repetedMetaArr['email_send'] = 0;
                    }
                    $value = date('Y-m-d H:i:s', strtotime('+' . $value . ' days'));
                }
                $value = str_replace('[first-name]', $firstName, $value);

                $repetedMetaArr[$key] = $value;
            }
            $repetedMeta[] = $repetedMetaArr;
        }

        $waterSchedule = get_field('watering_schedule', $plantID);
        $currentMonth = date('n');
        $repeatWaterMeta = $repeatWaterMetaArr = array();
        $active_water_schedule = 0;
        if ($waterSchedule) {
            foreach ($waterSchedule as $schedule) {
                if ($schedule['watering_schedule_month'] == $currentMonth) {
                    $days = $schedule['watering_schedule_frequency'];
                    $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

                    $repeatWaterMetaArr['date'] = $nextDay;
                    $repeatWaterMetaArr['answer'] = 0;
                    $active_water_schedule = 1;
                    $repeatWaterMeta[] = $repeatWaterMetaArr;
                }
            }
        }

        $fertilizingSchedule = get_field('fertilizing_schedule', $plantID);
        $currentMonth = date('n');
        $repeatfertilizingMeta = $repeatfertilizingMetaArr = array();
        $active_fertilizing_schedule = 0;
        if ($fertilizingSchedule) {
            foreach ($fertilizingSchedule as $schedule) {
                if ($schedule['fertilizing_schedule_month'] == $currentMonth) {
                    $days = $schedule['fertilizing_frequency'];
                    $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

                    $repeatfertilizingMetaArr['date'] = $nextDay;
                    $repeatfertilizingMetaArr['answer'] = 0;

                    $repeatfertilizingMeta[] = $repeatfertilizingMetaArr;
                    $active_fertilizing_schedule = 1;
                }
            }
        }

        $days = get_field('frequent_pruning', $plantID);
        $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

        $frequentPruningMetaArr = array();
        $frequentPruningMetaArr['date'] = $nextDay;
        $frequentPruningMetaArr['answer'] = 0;

        $frequentPruningMeta[] = $frequentPruningMetaArr;

        $numberOfPlants = check_plant($userID, $plantID) + 1;
        $my_post = array(
            'post_title'    => $post_data->post_title,
            'post_name'     => $userID . "_" . $post_data->post_name . "_" . $numberOfPlants,
            'post_status'   => 'publish',
            'post_type'     => $postType,
            'post_author'   => $userID,
            'meta_input'   => $metaArray,
        );
        $notification = $repetedMeta[0];
        // Insert my-plant post into the database
        $insertID = wp_insert_post($my_post);
        //send Email 
        $user = get_user_by('id', $userID);
        wp_mail($user->user_email, $notification['notification_title'], $notification['notification_description']);
        //print_r($repetedMeta);
        $update = update_field('mp_watering_schedule', $repeatWaterMeta, $insertID);
        $update2 = update_field('mp_notification_settings', $repetedMeta, $insertID);
        $update3 = update_field('mp_fertilizing_schedule', $repeatfertilizingMeta, $insertID);
        $update4 = update_field('frequent_pruning_schedule', $frequentPruningMeta, $insertID);

        update_post_meta($insertID, 'active_fertilizing_schedule', $active_fertilizing_schedule);
        update_post_meta($insertID, 'active_water_schedule', $active_water_schedule);
        update_post_meta($insertID, 'active_frequent_pruning', $active_frequent_pruning);

        echo  1;
    } else {
        echo 2;
    }
    wp_die();
}

add_action('wp_ajax_get_data', 'get_data');
add_action('wp_ajax_nopriv_get_data', 'get_data');
