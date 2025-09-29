<?php
function wpza_replace_repeater_field($where)
{
    $where = str_replace("meta_key = 'mp_notification_settings_$", "meta_key LIKE 'mp_notification_settings_%", $where);
    return $where;
}
add_filter('posts_where', 'wpza_replace_repeater_field');
function notification_send()
{
    $startDate = date('Y-m-d 00:00:00');
    $endDate = date('Y-m-d 23:59:59');
    $args = array(
        'numberposts'    => -1,
        'post_type' => 'my-plant',
        'post_status' => 'publish',
        'meta_query'    => array(
            array(
                'key'        => 'mp_notification_settings_$_plant_day',
                'compare'    => '>=',
                'value'        => $startDate,
            ),
            array(
                'key'        => 'mp_notification_settings_$_plant_day',
                'compare'    => '<=',
                'value'        => $endDate,
            ),
            array(
                'key'        => 'mp_notification_settings_$_email_send',
                'value'        => 0,
            )
        )
    );
    $total_number_of_email = 0;
    $total_data = array();
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            //echo $the_query->found_posts;die;
            $the_query->the_post();
            $userID = get_field('user');
            $user = get_user_by('id', $userID);
            $repeatedFieldName = 'mp_notification_settings';
            $link = 'https://aifarming.ca/wp-admin/post.php?post=' . get_the_ID() . '&action=edit';
            if (have_rows($repeatedFieldName)) :
                // Loop through rows.
                while (have_rows($repeatedFieldName)) :
                    the_row();
                    if (
                        get_sub_field('notification_type') == 'EM' &&
                        get_sub_field('plant_day') >= $startDate &&
                        get_sub_field('plant_day') <= $endDate &&
                        get_sub_field('email_send') != 1
                    ) {
                        $update = update_sub_field('email_send', 1);
                        wp_mail($user->user_email, get_sub_field('notification_title'), get_sub_field('notification_description'));
                        $total_number_of_email++;
                        $total_data[] = array(
                            "user_email" => $user->user_email,
                            "subject" => get_sub_field('notification_title'),
                            "url" => $link,
                        );
                        $type = 4;
                        $answer = array();
                        plant_history_create(get_the_ID(), $type, $answer);
                    }
                endwhile;
            endif;
        }
    }
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
        //wp_mail( 'tanzilur@sklentr.com', 'Daily Notification Email Report', $data, $headers );
    }
}
add_action('notification_send', 'notification_send');
if (!wp_next_scheduled('notification_send')) {
    wp_schedule_event(strtotime('08:30:00'), 'daily', 'notification_send');
}
