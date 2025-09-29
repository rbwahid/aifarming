<?php
if (!function_exists('post_exists')) {
    require_once(ABSPATH . 'wp-admin/includes/post.php');
}
function set_city_code()
{
    $xml = simplexml_load_file('https://dd.weather.gc.ca/citypage_weather/xml/siteList.xml');

    foreach ($xml->site as $item) {
        $attributes = $item->attributes();
        $codeAttr = $attributes->code;
        $zero = 0;
        $provinceData = $item->provinceCode;
        $nameData = $item->nameEn;
        $code = (string) $codeAttr[0];
        $province = (string) $provinceData[0];
        $name = (string) $nameData[0];
        $data[$province][] = array(
            'code' => $code,
            'name' => $name
        );
        $post_exist = post_exists($name, '', '', 'weather');

        if (!$post_exist) {
            $metaArray = array();
            $metaArray['code'] = $code;
            $metaArray['province'] = $province;
            $metaArray['fetch_url'] = 'https://dd.weather.gc.ca/citypage_weather/xml/' . $province . '/' . $code . '_e.xml';

            $my_post = array(
                'post_title'    => $name,

                'post_status'   => 'publish',
                'post_type'     => 'weather',
                'post_name'        => $code,
                'post_author'   => 1,
                'meta_input'   => $metaArray,
            );
            // Insert Takaful group post into the database
            $insertID = wp_insert_post($my_post);
        }
    }
}

add_action('set_city_code', 'set_city_code');
if (!wp_next_scheduled('set_city_code')) {
    wp_schedule_event(strtotime('08:30:00'), 'twicedaily', 'set_city_code');
}
