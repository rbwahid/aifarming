<?php
function create_myPlant()
{
    $user = wp_get_current_user();
    $pageID = 2771;
    $pageURL = get_permalink($pageID) . '?token=' . md5($user->user_email) . '&path=' . get_permalink();
    return $pageURL;
}
add_shortcode('create_myPlant', 'create_myPlant');

function plant_id()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    return url_to_postid($pageURL);
}
add_shortcode('plant_id', 'plant_id');

function plant_name()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    $id = url_to_postid($pageURL);
    if ($id) {
        return get_the_title($id);
    }
}
add_shortcode('plant_name', 'plant_name');

function plant_secret_id()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    $id = url_to_postid($pageURL);
    return ($id) ? md5($id) : "";
}
add_shortcode('plant_secret_id', 'plant_secret_id');

function plant_url()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    $pageID = url_to_postid($pageURL);
    return get_permalink($pageID);
}
add_shortcode('plant_url', 'plant_url');

function onboarding_plant_title()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    $pageID = url_to_postid($pageURL);
    return get_the_title($pageID);
}
add_shortcode('onboarding_plant_title', 'onboarding_plant_title');

function onboarding_plant_image()
{
    $user = wp_get_current_user();
    $pageURL = $_GET['path'];
    $pageID = url_to_postid($pageURL);
    $featured_img_url = get_the_post_thumbnail_url($pageID, 'full');
    return $featured_img_url;
}
add_shortcode('onboarding_plant_image', 'onboarding_plant_image');
