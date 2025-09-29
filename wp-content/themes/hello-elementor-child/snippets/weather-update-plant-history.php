<?php
function water_schedule_notification_create()
{
    $condition_check_arr = array("Mostly Cloudy", "Drifting Snow", "Blowing Snow");
    $args = array(
        'numberposts'    => -1,
        'post_type' => 'my-plant',
        'post_status' => 'publish'
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
            $city = get_field('user_city', 'user_' .  $userID);
            $weather_id = post_exists($city, '', '', 'weather');
            if ($weather_id) {
                $condition = get_field('condition', $weather_id);
                $current_milestone = get_field('current_milestone_stage');
                $args = array(
                    'numberposts'    => 1,
                    'post_type'     => 'plant-history',
                    'post_status'     => 'publish',
                    'order'         => 'DESC',
                    'meta_query' => array(
                        array(
                            "key"     => "user_plant",
                            "value" => get_the_ID()
                        ),
                        array(
                            "key"     => "milestone_stage",
                            "value" => $current_milestone
                        ),
                        array(
                            "key"     => "type",
                            "value" => 7
                        ),
                        array(
                            "key"     => "weather_condition",
                            "value" => $condition
                        )

                    )
                );
                $latest_post = get_posts($args);
                $lastCondition = "";
                $lastDate = date('Y-m-d 00:00:00', strtotime("-10 days"));
                if ($latest_post) {
                    foreach ($latest_post as $lp) {
                        $lastCondition = get_field("weather_condition", $lp->ID);
                        $lastDate = get_the_time('Y-m-d 00:00:00', $lp->ID);
                    }
                }
                $postPublish = 0;
                $compareDate = date('Y-m-d 00:00:00', strtotime("-3 days"));
                if (in_array($condition, $condition_check_arr)) {
                    if ($condition == $lastCondition && $compareDate >= $lastDate) {
                        $postPublish = 1;
                    } else if ($condition != $lastCondition) {
                        $postPublish = 1;
                    }
                }
                if ($postPublish == 1) {
                    $postType = 'plant-history';
                    $my_post = array(
                        'post_title'    => "Weather Update | " . $condition,
                        'post_status'   => 'publish',
                        'post_type'     => $postType,
                        'post_author'   => $userID,
                    );
                    $insertID = wp_insert_post($my_post);


                    update_field('plant', get_field('plant'), $insertID);
                    update_field('user_plant', get_the_ID(), $insertID);
                    update_field('user', $userID, $insertID);
                    update_field('type', 7, $insertID);
                    update_field('description', get_field('forecast_summary', $weather_id), $insertID);
                    update_field('articles', array(), $insertID);
                    update_field('weather_condition', $condition, $insertID);
                    update_field('milestone_stage', $current_milestone, $insertID);
                }
            }
        }
    }
}
//add_action('init','water_schedule_notification_create');
add_action('water_schedule_notification_create', 'water_schedule_notification_create');
if (!wp_next_scheduled('water_schedule_notification_create')) {
    wp_schedule_event(strtotime('06:30:00'), 'daily', 'water_schedule_notification_create');
}
