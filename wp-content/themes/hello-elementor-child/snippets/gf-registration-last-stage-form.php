<?php
add_filter('gform_required_legend', function ($legend, $form) {
    return '';
}, 10, 2);

add_action('gform_user_updated', 'change_role', 10, 4);
function change_role($user_id, $feed, $entry, $user_pass)
{
    $result = array();
    foreach ($entry as $key => $data) {
        if (is_numeric($key)) {
            if (is_int($key)) {
                $result[$key] = $data;
            } else {
                if ($data) {
                    $result[(int)$key][] = $data;
                }
            }
        }
    }
    $location = array('indoor_locations', 'outdoor_locations');
    foreach ($feed['meta']['userMeta'] as $data) {
        if (in_array($data['custom_key'], $location)) {
            update_field($data['custom_key'], $result[$data['value']], 'user_' . $user_id);
        }
    }
    update_user_meta($user_id, 'complete_step', 3);
}
function custom_redirects_last_stage()
{
    global $sklentr_variable;
    if (get_the_ID() == $sklentr_variable['registration_stage_3_page']  && !isset($_GET['elementor-preview'])) {
        if (!is_user_logged_in()) {
            wp_redirect($sklentr_variable['login_url']);
        }
        $userId = get_current_user_id();
        $current_step = get_user_meta($userId, 'complete_step', true);
        $final_step = $sklentr_variable['email_verification_step'];
        if ($current_step != 2) {
            $next_step = $current_step + 1;
            if ($current_step == $final_step) {
                wp_redirect($sklentr_variable['my_account_url']);
            }
            $step = 'registration_stage_' . $next_step . '_page';
            wp_redirect(get_permalink($sklentr_variable[$step])); //change this ID with Step page.	
        }
    }
}
add_action('template_redirect', 'custom_redirects_last_stage');
