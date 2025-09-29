<?php

function wpza_replace_repeater_field_2($where)
{
    $where = str_replace("meta_key = 'mp_watering_schedule_$", "meta_key LIKE 'mp_watering_schedule_%", $where);
    return $where;
}
add_filter('posts_where', 'wpza_replace_repeater_field_2');
function water_schedule_notification_send()
{
    $startDate = date('Y-m-d 00:00:00');
    $endDate = date('Y-m-d 23:59:59');
    $args = array(
        'numberposts'    => -1,
        'post_type' => 'my-plant',
        'post_status' => 'publish',
        'meta_query'    => array(
            array(
                'key'        => 'mp_watering_schedule_$_date',
                'compare'    => '>=',
                'value'        => $startDate,
            ),
            array(
                'key'        => 'mp_watering_schedule_$_date',
                'compare'    => '<=',
                'value'        => $endDate,
            ),
            array(
                'key'        => 'mp_watering_schedule_$_answer',
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
            fetch_weather($userID);
            $user = get_user_by('id', $userID);
            $repeatedFieldName = 'mp_watering_schedule';
            $link = 'https://aifarming.ca/wp-admin/post.php?post=' . get_the_ID() . '&action=edit';
            if (have_rows($repeatedFieldName)) :
                // Loop through rows.
                while (have_rows($repeatedFieldName)) :
                    the_row();
                    if (
                        get_sub_field('date') >= $startDate &&
                        get_sub_field('date') <= $endDate &&
                        get_sub_field('answer') != 1
                    ) {

                        $city = get_user_meta($userID, 'user_city', true);
                        $weather_id = post_exists($city, '', '', 'weather');
                        $cloud = get_field('cloud_precipitation', $weather_id);
                        if ($cloud) {
                            $headers = array('Content-Type: text/html; charset=UTF-8');
                            $data = "Hello $user->first_name,<br/><br/>
	<b>Weather forecast for today:</b><br/>" . $cloud . "<br/></br>

	Balance the water according to the weather today.<br/><br/> 

	To know more details about the watering, please login to your dashboard and select your plant for details.<br/><br/> 

	Regards,<br/>
	AIFarming Team";
                            wp_mail($user->user_email, 'Water Scheduling Email', $data, $headers);
                            $total_number_of_email++;
                            $total_data[] = array(
                                "user_email" => $user->user_email,
                                "subject" => 'Water Scheduling Email',
                                "url" => $link,
                            );
                        }
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
        //wp_mail( 'trrizvi07@gmail.com', 'Daily Notification Email Report', $data, $headers );
    }
}
add_action('water_schedule_notification_send', 'water_schedule_notification_send');
if (!wp_next_scheduled('water_schedule_notification_send')) {
    wp_schedule_event(strtotime('06:30:00'), 'daily', 'water_schedule_notification_send');
}
