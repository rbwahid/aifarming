<?php
add_filter('login_url', 'custom_login_url', PHP_INT_MAX);
function custom_login_url($login_url)
{
    global $sklentr_variable;
    $login_url = $sklentr_variable['login_url'];
    return $login_url;
}
