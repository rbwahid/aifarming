<?php
add_action('wp_nav_menu_items', 'change_logout_url', 10, 2);

function change_logout_url($items, $args)
{
    if ($args->menu != 'user-menu') {
        return $items;
    }
    $newItem = '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="' . wp_logout_url('/') . '" class="elementor-item">Logout</a></li>';
    $items .= $newItem;
    return $items;
}
