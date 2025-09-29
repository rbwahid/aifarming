<?php
add_action('after_setup_theme', 'process_variable');
function process_variable()
{
    global $sklentr_variable;

    //General Data
    $sklentr_variable['login_form_id'] = get_field('login_form_id', 'option');
    $sklentr_variable['registration_form_id'] = get_field('registration_form_id', 'option');
    $sklentr_variable['profile_form_id'] = get_field('profile_form_id', 'option');
    $sklentr_variable['login_url'] = get_field('login_url', 'option');
    $sklentr_variable['profile_url'] = get_field('profile_url', 'option');
    $sklentr_variable['my_account_url'] = get_field('my_account_url', 'option');
    $sklentr_variable['logo_url'] = get_field('logo_url', 'option');
    $sklentr_variable['mail_footer'] = get_field('mail_footer', 'option');

    //Theme Data
    $sklentr_variable['plant_action_id'] = get_field('plant_action_id', 'option');
    $sklentr_variable['plant_url'] = get_field('plant_url', 'option');
    $sklentr_variable['my_plant_url'] = get_field('my_plant_url', 'option');
    $sklentr_variable['successful_plant_select_msg'] = get_field('successful_plant_select_msg', 'option');
    $sklentr_variable['something_wrong_msg'] = get_field('something_wrong_msg', 'option');
    $sklentr_variable['login_first_msg'] = get_field('login_first_msg', 'option');
    $sklentr_variable['plant_manage_field_name'] = get_field('plant_manage_field_name', 'option');
    $sklentr_variable['searchandfilter_id'] = get_field('searchandfilter_id', 'option');

    //registration page
    $sklentr_variable['email_verification_step']    = 3;
    $sklentr_variable['email_verification_page_id']    = 793;
    $sklentr_variable['registration_stage_1_page']    = 3574;
    $sklentr_variable['registration_stage_2_page']    = 3600;
    $sklentr_variable['registration_stage_3_page']    = 3609;
}
function upload_file_to_media_library($file_path)
{
    // Check if the file exists
    if (!file_exists($file_path)) {
        return new WP_Error('file_not_found', __('The specified file does not exist.'));
    }

    // Get the file type
    $filetype = wp_check_filetype(basename($file_path), null);

    // Prepare an array of post data for the attachment
    $attachment = array(
        'guid'           => wp_upload_dir()['url'] . '/' . basename($file_path),
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert the attachment into the WordPress Media Library
    $attachment_id = wp_insert_attachment($attachment, $file_path);

    // Include the image.php file to make the wp_generate_attachment_metadata function available
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Generate the metadata for the attachment, and update the database record
    $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
    wp_update_attachment_metadata($attachment_id, $attachment_data);

    return $attachment_id;
}
function add_cond_to_where($where)
{
    //Replace ai_information_$ with repeater_slug_$
    $where = str_replace("meta_key = 'ai_information_$", "meta_key LIKE 'ai_information_%", $where);

    return $where;
}
add_filter('posts_where', 'add_cond_to_where');
function check_milestone_data($my_plant_id, $milestone_stage)
{
    $args = array(
        'p' => $my_plant_id,
        'post_type' => 'my-plant',
        'meta_query'        => array(
            array(
                'key'       => 'ai_information_$_milestone_stage',
                'value'     => $milestone_stage,
                'compare'   => '='
            )
        )
    );

    $query = new WP_Query($args);
    // The Loop
    return $query->post_count;
}
function fetch_milestone_data($my_plant_id, $milestone_stage)
{
    $myPlantUserID = get_field('user', $my_plant_id);
    $plantID = get_field('plant', $my_plant_id);
    if ($plantID && !check_milestone_data($my_plant_id, $milestone_stage)) {
        $repeaterfield = get_field('field_67276b7d82097', $plantID); //ai_information; post=3316
        $repeatedMeta = array();
        $initialStage = $milestone_stage;
        $day_0 = 0;
        $startingDate = date('Y-m-d H:i:s');
        foreach ($repeaterfield as $field) {
            $repeatedMetaArr = array();
            if ($field['milestone_stage'] == $initialStage) {
                foreach ($field as $key => $value) {
                    if ($key == 'information_day') {
                        if ($value == 0) {
                            $repeatedMetaArr['task_complete'] = true;
                            $day_0 = 1;
                        } else {
                            $repeatedMetaArr['task_complete'] = false;
                        }
                        $value = date('Y-m-d H:i:s', strtotime('+' . $value . ' days', strtotime($startingDate)));
                    }
                    $value = str_replace('[first_name]', $firstName, $value);

                    $repeatedMetaArr[$key] = $value;
                }
                $repeatedMeta[] = $repeatedMetaArr;
            }
        }
        //print_r($repeatedMeta);die;
        if ($day_0) {
            $send_email_date = "";
            $notification = $repeatedMeta[0];
            if ($notification['send_email']) {
                //send Email 
                $user = get_user_by('id', $userID);
                wp_mail($user->user_email, $notification['email_subject'], $notification['email_body']);
                $send_email_date = date('Y-m-d H:i:s');
            }
            plant_history_create_v2($my_plant_id, $notification, $send_email_date);
        }
        //print_r($repeatedMeta);
        if (!empty($repeatedMeta)) {
            $myPlantRepeaterField = get_field('field_672780b348ddd', $my_plant_id); //ai_information; post=3316
            $repeater = array_merge($myPlantRepeaterField, $repeatedMeta);
            update_field('field_672780b348ddd', $repeater, $my_plant_id); //My plant AI information update
            update_field('field_672b5f289bd9a', $initialStage, $my_plant_id); //Current Milestone Stage update
        }
    }
}
function plant_history_create($my_plant_id, $type, $answer)
{
    $typesAcfObj = get_field_object('field_6666c5f73014c');
    $typeTitle = $typesAcfObj['choices'];

    $userID = get_current_user_id();
    $myPlantUserID = get_field('user', $my_plant_id);
    $plantID = get_field('plant', $my_plant_id);
    if ($userID != $myPlantUserID) {
        return false;
    }
    $postType = 'plant-history';
    $my_post = array(
        'post_title'    => $typeTitle[$type] . " | " . get_the_title($my_plant_id),
        'post_status'   => 'publish',
        'post_type'     => $postType,
        'post_author'   => $userID,
    );
    $insertID = wp_insert_post($my_post);

    update_field('type', $type, $insertID);
    update_field('user', $userID, $insertID);
    update_field('user_plant', $my_plant_id, $insertID);
    update_field('plant', $plantID, $insertID);

    switch ($type) {
        case 1:
        case 2:
        case 3:
            update_field('answer_date', date('Y-m-d H:i:s'), $insertID);
            break;
        case 4:
            update_field('send_email_date', date('Y-m-d H:i:s'), $insertID);
            break;
        case 5:
            update_field('progress_update', $answer, $insertID);
            break;
    }

    return true;
}
function plant_history_create_v2($my_plant_id, $notification, $send_email_date)
{
    $typesAcfObj = get_field_object('field_6666c5f73014c');
    $typeTitle = $typesAcfObj['choices'];
    //$userID = get_current_user_id();
    $userID = get_field('user', $my_plant_id);
    $plantID = get_field('plant', $my_plant_id);

    $type = $notification["type_of_the_section"];
    $title_of_the_section = $notification["title_of_the_section"];
    $description = $notification["description"];
    $articles = $notification["articles"];
    $milestone_stage = $notification["milestone_stage"];
    $milestone_question = $notification["milestone_question"];

    $postType = 'plant-history';
    $my_post = array(
        'post_title'    => $title_of_the_section,
        'post_status'   => 'publish',
        'post_type'     => $postType,
        'post_author'   => $userID,
    );
    $insertID = wp_insert_post($my_post);


    update_field('plant', $plantID, $insertID);
    update_field('user_plant', $my_plant_id, $insertID);
    update_field('user', $userID, $insertID);
    update_field('type', $type, $insertID);
    update_field('description', $description, $insertID);
    update_field('articles', $articles, $insertID);
    update_field('send_email_date', $send_email_date, $insertID);
    update_field('milestone_stage', $milestone_stage, $insertID);



    switch ($type) {
        case 1: //water schedule
        case 2: //Fertilizing Schedule
        case 3: //Frequent Pruning Schedule
            update_field('answer_date', date('Y-m-d H:i:s'), $insertID);
            break;
        case 4: //Notification
            update_field('send_email_date', date('Y-m-d H:i:s'), $insertID);
            break;
        case 5: //Progress Update
            update_field('progress_update', $answer, $insertID);
            break;
        case 6: //Start Milestone
            break;
        case 7: //Weather Update
            break;
        case 8: //Complete Milestone
            //check all the question answer right or wrong then run this code.
            if (!empty($milestone_question)) {
                update_field('milestone_question', $milestone_question, $insertID);
                update_field('question_active', 1, $my_plant_id);
                update_field('question_post', $insertID, $my_plant_id);
            } else {
                update_field('question_active', 0, $my_plant_id);
                update_field('question_post', NULL, $my_plant_id);
                $milestone_stage++;
                update_field('current_milestone_stage', $milestone_stage, $my_plant_id);
                fetch_milestone_data($my_plant_id, $milestone_stage);
            }
            /*
			update_field('milestone_question', $milestone_question, $insertID);
			update_field('question_active', 1, $my_plant_id);
			update_field('question_post',$insertID,$my_plant_id);
			$milestone_stage++;
			*/
            //fetch_milestone_data($my_plant_id, $milestone_stage);
            break;
        default:
            break;
    }
}

add_filter('rest_authentication_errors', function ($result) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }

    if (!current_user_can('administrator')) {
        return new WP_Error('rest_forbidden', 'REST API is restricted to administrators.', array('status' => 403));
    }

    return $result;
});
