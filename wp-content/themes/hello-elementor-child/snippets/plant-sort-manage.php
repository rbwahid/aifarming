<?php
function filter_plants($query_args, $sfid)
{
    //if search form ID = 6128, the do something with this query
    //localID - 1033 & Live ID - 1024
    global $sklentr_variable;
    if ($sfid == $sklentr_variable['searchandfilter_id']) {
        $meta_query = array(
            'relation' => 'OR',
            'available_to_manage_not_exists' => [ // named clause
                'key' => 'available_to_manage',
                'compare' => 'NOT EXISTS',
            ],
            'available_to_manage_exists' => [ // named clause
                'key' => 'available_to_manage',
                'compare' => 'EXISTS',
            ],
        );
        $query_args['meta_query'] = array($meta_query);
        $query_args['orderby'] = array(
            'available_to_manage_exists' => 'DESC',
            'available_to_manage_not_exists' => 'DESC',
        );
    }

    return $query_args;
}
add_filter('sf_edit_query_args', 'filter_plants', 20, 2);
