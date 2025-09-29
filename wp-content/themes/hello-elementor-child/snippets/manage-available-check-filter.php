<?php
function custom_manage_check_callback($query)
{
    // Modify the posts query here
    $query->set('meta_key', 'available_to_manage');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'DESC');
}
add_action('elementor/query/manage_available_check', 'custom_manage_check_callback');

function custom_available_check_callback($query)
{
    // Get current meta Query
    $meta_query = $query->get('meta_query');

    // If there is no meta query when this filter runs, it should be initialized as an empty array.
    if (!$meta_query) {
        $meta_query = [];
    }

    // Append our meta query
    $meta_query[] = [
        'key' => 'available_to_manage',
        'value' => 1,
        'compare' => '=',
    ];

    $query->set('meta_query', $meta_query);
}
add_action('elementor/query/available_plants', 'custom_available_check_callback');
