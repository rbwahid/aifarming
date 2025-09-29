<?php
function acf_load_city_field_choices($field)
{

    // Reset choices
    $field['choices'] = array();

    $args = array('post_type' => 'weather', 'post_status' => 'publish', 'numberposts' => -1, 'order' => 'ASC', 'orderby' => 'title');

    $cities = get_posts($args);
    if ($cities) {
        foreach ($cities as $city) :
            $field['choices'][$city->post_title] = $city->post_title;
        endforeach;
    }


    // Return the field
    return $field;
}

add_filter('acf/load_field/name=user_city', 'acf_load_city_field_choices');
