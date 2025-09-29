<?php
function my_wp_nav_menu_args($args = '')
{
    if (is_user_logged_in()) {
        // Logged in menu to display
        //$args['menu'] = 18;
        $args['theme_location'] = 'menu-1';
    } else {
        // Non-logged-in menu to display
        $args['menu'] = 10;
    }
    return $args;
}
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args');
