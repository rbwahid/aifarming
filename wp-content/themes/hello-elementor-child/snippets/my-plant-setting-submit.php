<?php
function settingSubmit()
{
    if (is_singular('my-plant')) {
?>
        <script>
            jQuery(document).ready(function($) {
                $('body').on("click", ".my_plant_settings_btn", function(e) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "/wp-json/my-plant/v1/settings/<?php echo get_the_ID(); ?>",
                        data: $('.my_plant_settings').serialize(),
                        success: function(response) {
                            msg = '<div class="success-message"> Data update successfully.</div>'
                            $('.my_plant_settings').append(msg)
                            window.location.href = "<?php echo get_permalink(); ?>";
                            setTimeout(function() {
                                $('.success-message').fadeOut('fast');
                            }, 1000);

                        }
                    });
                });
            })
        </script>
<?php
    }
}
add_action('wp_head', 'settingSubmit');

add_action('rest_api_init', function () {
    register_rest_route('my-plant/v1', 'settings/(?P<post_id>\d+)', array(
        'methods'  => 'POST',
        'callback' => 'set_my_plant_settings',
        'permission_callback' => 'check_user_permission',
        'current_user' => get_current_user_id()
    ));
});
function set_my_plant_settings($request)
{
    $post_id = $request['post_id'];

    $post = get_post($post_id);
    if (empty($post)) {
        return new WP_Error('empty_post', 'There are no post to display', array('status' => 404));
    }
    $params = $request->get_body_params();
    foreach ($params as $key => $val) {
        update_post_meta($post_id,  $key, $val);
    }
    $response = new WP_REST_Response(array("status" => 1));
    $response->set_status(200);

    return $response;
}
function check_user_permission($request)
{
    $userID = $request->get_attributes()['current_user'];

    $postID = $request->get_param('post_id');
    $postUserID = get_field('user', $postID);
    if ($userID == $postUserID['ID']) {
        return true;
    }
}
?>