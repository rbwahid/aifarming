<?php
add_filter('wp_mail', 'mails_header_footer', 10, 1);
function mails_header_footer($args)
{
    global $sklentr_variable;
    //$to = $args['to'];
    //$args['subject']
    $args['message'];
    //$args['headers']
    //$args['attachments']
    $site_title = get_bloginfo('name');
    $header = '<div style="margin: 0 auto; max-width: 600px;"><div style="padding:10px;text-align: left;"><img src="' . $sklentr_variable["logo_url"] . '" alt="' . $site_title . '" /></div><div style="padding: 50px 20px">';
    $footer = '</div><div style="padding: 20px;">' . $sklentr_variable["mail_footer"] . '</div></div>';
    $args['message'] = $header . $args['message'] . $footer;
    return $args;
}
