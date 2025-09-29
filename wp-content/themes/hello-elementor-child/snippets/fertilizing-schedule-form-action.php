<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_fertilizing_schedule', 10, 2);
function general_elementor_form_fertilizing_schedule($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname');
    $form_name = $record->get_form_settings('form_id');

    if ('fertilizing_schedule' !== strtolower($form_name)) {
        return;
    }
    $user = get_current_user_id();
    $current_user_data = get_userdata($user);
    $user_email = $current_user_data->user_email;
    $user_name = $current_user_data->first_name;
    $permalink = get_permalink();

    $allFields     = $record->get('fields');

    $fertilizing_schedule_option = $allFields["fertilizing_schedule_option"]['value'];

    if ($fertilizing_schedule_option) {
        $postID =  get_the_ID();
        $rows = get_field('mp_fertilizing_schedule', $postID);
        $newRows = array();
        if ($rows) {
            foreach ($rows as $row) {
                if (!$row['answer']) {
                    $row['answer'] = 1;
                    $row['answer_date'] = date('Y-m-d');
                    update_post_meta($postID, 'active_fertilizing_schedule', 0);
                }
                $newRows[] = $row;
            }
        }
        $plantID = get_field('plant', $postID);

        $fertilizingSchedule = get_field('fertilizing_schedule', $plantID);
        $currentMonth = date('n');
        $repeatfertilizingMeta = $repeatfertilizingMetaArr = array();

        if ($fertilizingSchedule) {
            foreach ($fertilizingSchedule as $schedule) {
                if ($schedule['fertilizing_schedule_month'] == $currentMonth) {
                    $days = $schedule['fertilizing_frequency'];
                    $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

                    $repeatfertilizingMetaArr['date'] = $nextDay;
                    $repeatfertilizingMetaArr['answer'] = 0;

                    $newRows[] = $repeatfertilizingMetaArr;

                    update_post_meta($plantID, 'active_fertilizing_schedule', 1);
                }
            }
            $data = "Dear " . $user_name . ",<br/>Congratulations on successfully completing your fertilizing schedule for your plant(s)! Regular fertilization is crucial for the health and growth of your plants, and you're doing a fantastic job.<br/><br/>
					<b>Next Fertilizing Schedule:</b></br>
	<b>Plant Name:</b> <a href='" . $permalink . "'>" . get_the_title() . "</a></br>
	<b>Next Fertilizing Date and Time:</b> " . date('F j, Y', strtotime($nextDay)) . "</br></br>Here are a few tips to keep in mind for your next fertilizing session:<br/><ul><li>Use the recommended amount of fertilizer to avoid over-fertilizing.</li>
	<li>Ensure the soil is moist before applying fertilizer.</li>
	<li>Adjust the frequency based on the plant's growth stage and seasonal needs.</li></ul><br/>Thank you for being a dedicated plant parent. If you have any questions or need further assistance, feel free to contact our support team.<br/><br/>Happy Gardening!<br/><br/>Best regards,<br/>
	AI Farming Team";
        } else {
            $data = "Dear " . $user_name . ",<br/>Congratulations on successfully completing your fertilizing schedule for your plant(s)! Regular fertilization is crucial for the health and growth of your plants, and you're doing a fantastic job.<br/><br/>Here are a few tips to keep in mind for your next fertilizing session:<br/><ul><li>Use the recommended amount of fertilizer to avoid over-fertilizing.</li>
	<li>Ensure the soil is moist before applying fertilizer.</li>
	<li>Adjust the frequency based on the plant's growth stage and seasonal needs.</li></ul><br/>Thank you for being a dedicated plant parent. If you have any questions or need further assistance, feel free to contact our support team.<br/><br/>Happy Gardening!<br/><br/>Best regards,<br/>
	AI Farming Team";
        }

        $update = update_field('mp_fertilizing_schedule', $newRows, $postID);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($user_email, "Great Job! You've Completed Your Fertilizing Schedule", $data, $headers);
    }
    $type = 2;
    $answer = array();
    plant_history_create(get_the_ID(), $type, $answer);
}
