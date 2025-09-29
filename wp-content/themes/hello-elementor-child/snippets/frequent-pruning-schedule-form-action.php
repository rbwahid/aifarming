<?php
add_action('elementor_pro/forms/new_record',  'general_elementor_form_frequent_pruning', 10, 2);
function general_elementor_form_frequent_pruning($record, $ajax_handler)
{
    global $sklentr_variable;
    $ignore_fields = array('fname', 'lname');
    $form_name = $record->get_form_settings('form_id');

    if ('frequent_pruning_schedule' !== strtolower($form_name)) {
        return;
    }

    $user = get_current_user_id();
    $current_user_data = get_userdata($user);
    $user_email = $current_user_data->user_email;
    $user_name = $current_user_data->first_name;
    $permalink = get_permalink();

    $allFields     = $record->get('fields');


    $postID =  get_the_ID();
    $rows = get_field('mp_watering_schedule', $postID);
    $newRows = array();
    if ($rows) {
        foreach ($rows as $row) {
            if (!$row['answer']) {
                $row['answer'] = 1;
                $row['answer_date'] = date('Y-m-d');
                update_post_meta($postID, 'active_frequent_pruning', 0);
            }
            $newRows[] = $row;
        }
    }
    $plantID = get_field('plant', $postID);

    $days = get_field('frequent_pruning', $plantID);
    $frequentPruningMeta = $frequentPruningMetaArr = array();


    $nextDay = wp_date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));

    $frequentPruningMetaArr['date'] = $nextDay;
    $frequentPruningMetaArr['answer'] = 0;

    update_post_meta($plantID, 'active_frequent_pruning', 1);

    $newRows[] = $frequentPruningMetaArr;
    $data = "Dear " . $user_name . ",<br/>Congratulations on successfully completing your pruning schedule for your plant(s)! Regular pruning is essential for maintaining the health and appearance of your plants, and you're doing an excellent job.<br/><br/>
					<b>Next Pruning Schedule:</b></br>
	<b>Plant Name:</b> <a href='" . $permalink . "'>" . get_the_title() . "</a></br>
	<b>Next Pruning Date and Time:</b> " . date('F j, Y', strtotime($nextDay)) . "</br></br>Here are a few tips to keep in mind for your next pruning session:<br/><ul><li>Use clean, sharp tools to make precise cuts.</li>
	<li>Remove dead, damaged, or diseased branches to promote healthy growth.</li>
	<li>Prune strategically to shape your plant and encourage new growth.</li></ul><br/>Thank you for being a dedicated plant parent. If you have any questions or need further assistance, feel free to contact our support team.<br/><br/>Happy Gardening!<br/><br/>Best regards,<br/>
	AI Farming Team";
    $update = update_field('frequent_pruning_schedule', $newRows, $postID);
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($user_email, "Great Job! You've Completed Your Fertilizing Schedule", $data, $headers);

    $type = 3;
    $answer = array();
    plant_history_create(get_the_ID(), $type, $answer);
}
