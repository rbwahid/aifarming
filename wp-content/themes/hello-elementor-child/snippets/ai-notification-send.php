<?php
function ai_replace_repeater_field($where)
{
    $where = str_replace("meta_key = 'ai_information_$", "meta_key LIKE 'ai_information_%", $where);
    return $where;
}
add_filter('posts_where', 'ai_replace_repeater_field');
function ai_notification_send()
{
    $startDate = date('Y-m-d 00:00:00', strtotime('-7 days'));
    $endDate = date('Y-m-d 23:59:59');
    $args = array(
        'numberposts'    => -1,
        'post_type' => 'my-plant',
        'post_status' => 'publish',
        'meta_query'    => array(
            array(
                'key'        => 'ai_information_$_information_day',
                'compare'    => '>=',
                'value'        => $startDate,
            ),
            array(
                'key'        => 'ai_information_$_information_day',
                'compare'    => '<=',
                'value'        => $endDate,
            ),
            array(
                'key'        => 'ai_information_$_task_complete',
                'value'        => 0,
            )
        )
    );
    $total_number_of_email = 0;
    $total_data = array();
    $the_query = new WP_Query($args);
    //print_r($the_query);
    //error_log( $the_query->found_posts );die;
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $userID = get_field('user');
            $user = get_user_by('id', $userID);
            $current_milestone_stage = get_field('current_milestone_stage');
            $repeatedFieldName = 'ai_information';
            $link = 'https://aifarming.ca/wp-admin/post.php?post=' . get_the_ID() . '&action=edit';
            if (have_rows($repeatedFieldName)) :
                // Loop through rows.
                while (have_rows($repeatedFieldName)) :
                    the_row();
                    if (
                        get_sub_field('information_day') >= $startDate &&
                        get_sub_field('information_day') <= $endDate &&
                        get_sub_field('task_complete') != 1 &&
                        get_sub_field('milestone_stage') ==  $current_milestone_stage
                    ) {
                        $send_email_date = "";
                        if (get_sub_field('send_email')) {
                            wp_mail($user->user_email, get_sub_field('email_subject'), get_sub_field('email_body'));
                            $send_email_date = date('Y-m-d H:i:s');
                            $total_number_of_email++;
                            $total_data[] = array(
                                "user_email" => $user->user_email,
                                "subject" => get_sub_field('email_subject'),
                                "url" => $link,
                            );
                        }
                        $notification = get_row();
                        $notificationData = array();
                        foreach ($notification as $key => $rowData) {
                            $subField = get_sub_field_object($key);
                            $notificationData[$subField['_name']] = $rowData;
                        }
                        plant_history_create_v2(get_the_ID(), $notificationData, $send_email_date);
                        $update = update_sub_field('task_complete', 1);
                    }
                endwhile;
            endif;
        }
    }
    //echo "done!";die;
    if ($total_number_of_email) {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $data = 'Dear Admin,<br/><br/>This is an automated report of the notification emails sent from our website today.<br/>
		<b>Date:</b> ' . date('F j, Y') . '<br/>
		<b>Total Notification Emails Sent:</b> ' . $total_number_of_email . '<br/><br/>';

        if ($total_data) {
            $data .= '<table border="1" style="width:100%"><tr><th>Email</th><th>Subject</th><th>Link</th></tr>';
            foreach ($total_data as $tdata) {
                $data .= '<tr><td>' . $tdata["user_email"] . '</td><td>' . $tdata["subject"] . '</td><td>' . $tdata["url"] . '</td></tr>';
            }
            $data .= '</table>';
        }

        $data .= '<br/><br/>Thank you.';
        wp_mail('rishad@sklentr.com', 'Daily Notification Email Report', $data, $headers);
        wp_mail('tanzilur@sklentr.com', 'Daily Notification Email Report', $data, $headers);
    }
}
//add_action('init', 'ai_notification_send');
add_action('ai_notification_send', 'ai_notification_send');
if (!wp_next_scheduled('ai_notification_send')) {
    wp_schedule_event(strtotime('08:30:00'), 'daily', 'ai_notification_send');
}
