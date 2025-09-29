<?php
add_action('template_redirect', 'reset_password_check');

function reset_password_check()
{
    if (is_page('reset-password')) {
        $rp_cookie       = 'wp-resetpass-' . COOKIEHASH;
        $user = false;
        if (isset($_GET['key']) && isset($_GET['login'])) {
            $value = sprintf('%s:%s', wp_unslash($_GET['login']), wp_unslash($_GET['key']));
            setcookie($rp_cookie, $value, 0, "/", COOKIE_DOMAIN, is_ssl(), true);

            wp_safe_redirect(remove_query_arg(array('key', 'login')));
            exit;
        }
        if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
            list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);

            $user = check_password_reset_key($rp_key, $rp_login);
        } else {
            $user = false;
        }
        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(site_url('/lost-password?error=expiredkey'));
            } else {
                wp_redirect(site_url('/login'));
            }
        }
    }
}

add_action('elementor_pro/forms/new_record',  'general_elementor_form_user_rp', 10, 2);
function general_elementor_form_user_rp($record, $ajax_handler)
{
    global $sklentr_variable;
    $rp_cookie       = 'wp-resetpass-' . COOKIEHASH;
    $errors    = new WP_Error();
    $form_name = $record->get_form_settings('form_id');

    if ('resetpassword_form' !== strtolower($form_name)) {
        return;
    }

    $allFields     = $record->get('fields');

    $password = $allFields["pass1"]['value'];
    $getPasswordError = '';
    if (!empty($password)) {
        $password = trim($password);

        if (empty($password)) {
            $getPasswordError = 'The password cannot be a space or all spaces.';
        }
    }
    if ($getPasswordError) {
        $ajax_handler->add_error_message($getPasswordError);
        $ajax_handler->is_success = false;
        return;
    } else {
        list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2);

        $user = check_password_reset_key($rp_key, $rp_login);
        if (!$user || is_wp_error($user)) {
            if ($user && $user->get_error_code() === 'expired_key') {
                wp_redirect(site_url('/lost-password?error=expiredkey'));
            } else {
                wp_redirect(site_url('/login'));
            }
        }
        reset_password($user, $password);
        setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, "/", COOKIE_DOMAIN, is_ssl(), true);
    }
}
