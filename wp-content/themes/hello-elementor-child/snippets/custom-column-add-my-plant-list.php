<?php
$post_type = 'my-plant';
// Register the columns.
add_filter("manage_{$post_type}_posts_columns", function ($defaults) {
    foreach ($defaults as $key => $val) {
        $newData[$key] = $val;
        if ($key == "title") {
            $newData['user'] = 'User';
        }
    }
    return $newData;
});
// Handle the value for each of the new columns.
add_action("manage_{$post_type}_posts_custom_column", function ($column_name, $post_id) {
    if ($column_name == 'user') {
        $userID = get_field('user', $post_id);
        $user = get_user_by('id', $userID);
        echo '<a href="' . admin_url('edit.php?post_type=my-plant&user=' . $userID) . '">' . $user->first_name . " " . $user->last_name . '</a>';
    }
}, 10, 2);
add_action('restrict_manage_posts', 'user_filtering', 10);
function user_filtering($post_type)
{
    if ('my-plant' !== $post_type) {
        return; //filter your post
    }
    $selected = '';
    $request_attr = 'user';
    if (isset($_REQUEST[$request_attr])) {
        $selected = $_REQUEST[$request_attr];
    }
    $users = get_users();
    //build a custom dropdown list of values to filter by
    echo '<select id="user" name="user">';
    echo '<option value="0"> Show all users </option>';
    foreach ($users as $user) {
        $select = ($user->ID == $selected) ? ' selected="selected"' : '';
        echo '<option value="' . $user->ID . '"' . $select . '>' . $user->first_name . ' ' . $user->last_name . ' </option>';
    }
    echo '</select>';
}
add_action('pre_get_posts', 'custom_sortable');

function custom_sortable($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    global $pagenow;
    $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';

    if ($post_type == 'my-plant' && $pagenow == 'edit.php' && isset($_GET['user']) && !empty($_GET['user'])) {
        $query->set('meta_query', [
            [
                'key' => 'user',
                'value' => $_GET['user'],
                'compare' => '='
            ]
        ]);
    }
}
