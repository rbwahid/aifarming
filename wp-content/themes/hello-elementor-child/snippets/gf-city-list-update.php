<?php
add_filter('gform_pre_render_4', 'populate_city_dropdown');
add_filter('gform_pre_validation_4', 'populate_city_dropdown');
function populate_city_dropdown($form)
{
    foreach ($form['fields'] as &$field) {
        // Match by parameter name (you could also match by field ID)
        if ($field->inputName != 'gfur_field_15') {
            continue;
        }
        $cities = get_posts([
            'post_type'      => 'weather',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC'
        ]);

        $choices = [];
        $user_city = get_field('field_6530ffb618c7b', 'user_' . get_current_user_id());
        foreach ($cities as $city) {
            $choices[] = [
                'text'  => $city->post_title,
                'value' => $city->post_title, // or $city->post_title if preferred
                'isSelected' => ($city->post_title == $user_city)
            ];
        }

        $field->choices = $choices;
    }

    return $form;
}
