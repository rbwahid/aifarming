<?php
function my_plant_timeline($query)
{
    // Get current meta Query
    $meta_query = $query->get('meta_query');

    // If there is no meta query when this filter runs, it should be initialized as an empty array.
    if (!$meta_query) {
        $meta_query = [];
    }

    // Append our meta query
    $meta_query[] = [
        'key' => 'user_plant',
        'value' => get_the_ID(),
        'compare' => '=',
    ];

    $query->set('meta_query', $meta_query);
}
add_action('elementor/query/my_plant_timeline', 'my_plant_timeline');
