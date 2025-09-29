<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_water_schedule', 10, 2);
function general_elementor_form_water_schedule($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname');
    $form_name = $record->get_form_settings('form_id');

    if ('water_schedule' !== strtolower($form_name)) {
        return;
    }

    $user = get_current_user_id();
    $current_user_data = get_userdata($user);
    $user_email = $current_user_data->user_email;
    $user_name = $current_user_data->first_name;
    $permalink = get_permalink();

    $allFields     = $record->get('fields');

    $water_schedule_option = $allFields["water_schedule_option"]['value'];


    if ($water_schedule_option) {
        $postID =  get_the_ID();
        $rows = get_field('mp_watering_schedule', $postID);
        $newRows = array();
        if ($rows) {
            foreach ($rows as $row) {
                if (!$row['answer']) {
                    $row['answer'] = 1;
                    $row['answer_date'] = date('Y-m-d');
                    update_post_meta($postID, 'active_water_schedule', 0);
                }
                $newRows[] = $row;
            }
        }
        $plantID = get_field('plant', $postID);

        $waterSchedule = get_field('watering_schedule', $plantID);
        $currentMonth = date('n');
        $repeatWaterMeta = $repeatWaterMetaArr = array();
        if ($waterSchedule) {
            foreach ($waterSchedule as $schedule) {
                if ($schedule['watering_schedule_month'] == $currentMonth) {
                    $days = $schedule['watering_schedule_frequency'];
                    $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

                    $repeatWaterMetaArr['date'] = $nextDay;
                    $repeatWaterMetaArr['answer'] = 0;

                    update_post_meta($postID, 'active_water_schedule', 1);

                    $newRows[] = $repeatWaterMetaArr;
                }
            }
            $data = "Dear " . $user_name . ",<br/>Congratulations on successfully completing your watering schedule for your plant! Keeping up with the watering schedule is essential for the health and growth of your plants, and you're doing a fantastic job.<br/><br/>
				<b>Next Watering Schedule:</b></br>
<b>Plant Name:</b> <a href='" . $permalink . "'>" . get_the_title() . "</a></br>
<b>Next Watering Date and Time:</b> " . date('F j, Y', strtotime($nextDay)) . "</br></br>Remember, consistent care is the key to thriving plants. Here are a few tips to keep in mind:<br/><ul><li>Always check the soil moisture before watering.</li>
<li>Ensure your plant is in a well-draining pot.</li>
<li>Adjust the watering amount based on the season and plant needs.</li></ul><br/>Thank you for being a dedicated plant parent. If you have any questions or need further assistance, feel free to contact our support team.<br/><br/>Happy Gardening!<br/><br/>Best regards,<br/>
AI Farming Team";
        } else {
            $data = "Dear " . $user_name . ",<br/>Congratulations on successfully completing your watering schedule for your plant! Keeping up with the watering schedule is essential for the health and growth of your plants, and you're doing a fantastic job.<br/><br/>Remember, consistent care is the key to thriving plants. Here are a few tips to keep in mind:<br/><ul><li>Always check the soil moisture before watering.</li>
<li>Ensure your plant is in a well-draining pot.</li>
<li>Adjust the watering amount based on the season and plant needs.</li></ul><br/>Thank you for being a dedicated plant parent. If you have any questions or need further assistance, feel free to contact our support team.<br/><br/>Happy Gardening!<br/><br/>Best regards,<br/>
AI Farming Team";
        }
        $update = update_field('mp_watering_schedule', $newRows, $postID);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($user_email, 'Congratulations on Completing Your Watering Schedule!', $data, $headers);
    }
    $type = 1;
    $answer = array();
    plant_history_create(get_the_ID(), $type, $answer);
}
