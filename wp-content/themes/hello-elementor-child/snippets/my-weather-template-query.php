<?php
if (!function_exists('post_exists')) {
    require_once(ABSPATH . 'wp-admin/includes/post.php');
}
function my_weather_query($query)
{
    $city = get_user_meta(get_current_user_id(), 'user_city', true);
    $weather_id = post_exists($city, '', '', 'weather');
    if ($weather_id) {
        $query->set('p', $weather_id);
    }
}
add_action('elementor/query/my_weather', 'my_weather_query');
