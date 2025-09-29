<?php
function plant_select_link()
{
    return 'data-plant-id | ' . get_the_ID();
}
add_shortcode('plant_link', 'plant_select_link');
//plant link shortcode
function plant_id_check()
{
    global $sklentr_variable;
    return get_field($sklentr_variable['plant_manage_field_name']) ? "" : "hide";
}
add_shortcode('plant_id_check', 'plant_id_check');

function myPlant()
{
    global $sklentr_variable;
    if (!is_user_logged_in()) {
        return 0;
    }
    $userID = get_current_user_id();
    $args = array(
        'post_type' => 'my-plant',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'user',
                'value' => $userID,
                'compare' => '='
            )
        ),
    );
    $myPlantPosts = new WP_Query($args);
    ob_start();
    if ($myPlantPosts->have_posts()) {
        echo "<div class='row my-plant-row'>";
        while ($myPlantPosts->have_posts()) {
            $myPlantPosts->the_post();

            $plantID = get_field('plant');
            $getPlantData = get_post($plantID);
            $featureImage =  get_the_post_thumbnail_url($plantID, 'full');
            $description = get_field('plant_description', $plantID);
            $site = get_field('site', $myPlantPosts->ID);
            $plant_site = get_field('plant_site', $myPlantPosts->ID);
            $rows = get_field('mp_notification_settings', $myPlantPosts->ID);
            $startDate = "";
            if ($rows) {
                $row = $rows[0];
                $startDate = date('F j, Y', strtotime($row['plant_day']));
            }
?>
            <div class="col">
                <div class="feature-image">
                    <img src="<?php echo $featureImage; ?>" alt="<?php the_title(); ?>" />
                </div>
                <h3><?php the_title(); ?></h3>
                <p class="site"><?php echo ($plant_site) ?? $site; ?></p>
                <p class="start_date"><?php echo $startDate; ?></p>
                <div class="manage-button">
                    <a href="<?php the_permalink(); ?>" class="btn">Manage</a>
                </div>
            </div>
<?php
        }
        echo "</div>";
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('show_my_plant', 'myPlant');


function repeater_list($atts)
{
    $atts = shortcode_atts(array(
        'field' => '',
        'sub_field' => ''
    ), $atts, 'repeater_list');
    ob_start();
    // Check rows exists.
    $field = $atts['field'];
    $subfield = $atts['sub_field'];
    if (have_rows($field)) :

        echo '<div class="dce-acf-repeater"><table class="dce-acf-repeater-table"><tbody>';
        while (have_rows($field)) : the_row();

            // Load sub field value.
            $sub_value = get_sub_field($subfield);
            echo '<tr><td><a href="' . get_permalink($sub_value->ID) . '">' . $sub_value->post_title . '</a></td></tr>';

        // End loop.
        endwhile;
        echo '</tbody></table></div>';

    // No value.
    else :
        echo 'Coming soon';
    endif;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('repeater_list', 'repeater_list');

function acf_post_obj($atts)
{
    $atts = shortcode_atts(array(
        'field' => '',
        'separator' => ','
    ), $atts, 'repeater_list');
    ob_start();
    // Check rows exists.
    $field = $atts['field'];
    $separator = $atts['separator'];
    $featured_posts = get_field($field);
    if ($featured_posts) :
        $i = 0;
        foreach ($featured_posts as $featured_post) :
            $permalink = get_permalink($featured_post->ID);
            $title = get_the_title($featured_post->ID);
            if ($i > 0) {
                echo $separator . ' ';
            }
            echo '<a href="' . esc_url($permalink), '">' . esc_html($title) . '</a>';
            $i++;
        endforeach;
    endif;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('acf_post_obj', 'acf_post_obj');

function seed_depth()
{
    $seed_depth_from = get_field('seed_depth_from');
    $seed_depth_to = (get_field('seed_depth_to')) ? ' - ' . get_field('seed_depth_to') : '';
    return $seed_depth_from . $seed_depth_to;
}
add_shortcode('seed_depth', 'seed_depth');

function germination_soil_temp()
{
    $seed_depth_from = get_field('germination_soil_temp_from') . 'F';
    $seed_depth_to = (get_field('germination_soil_temp_to')) ? ' - ' . get_field('germination_soil_temp_to') . 'F' : '';
    return $seed_depth_from . $seed_depth_to;
}
add_shortcode('germination_soil_temp', 'germination_soil_temp');
//spacing_beds_from
function spacing_beds()
{
    $spacing_beds_from = get_field('spacing_beds_from') . 'cm';
    $spacing_beds_to = (get_field('spacing_beds_to')) ? ' - ' . get_field('spacing_beds_to') . 'cm' : '';
    return $spacing_beds_from . $spacing_beds_to;
}
add_shortcode('spacing_beds', 'spacing_beds');

function growing_soil_temp()
{
    $growing_soil_temp_from = get_field('growing_soil_temp_from') . 'F';
    $growing_soil_temp_to = (get_field('growing_soil_temp_to')) ? ' - ' . get_field('growing_soil_temp_to') . 'F' : '';
    return $growing_soil_temp_from . $growing_soil_temp_to;
}
add_shortcode('growing_soil_temp', 'growing_soil_temp');

function ph_range()
{
    $ph_range_from = get_field('ph_range_from');
    $ph_range_to = (get_field('ph_range_to')) ? ' - ' . get_field('ph_range_to') : '';
    return $ph_range_from . $ph_range_to;
}
add_shortcode('ph_range', 'ph_range');

function user_login_check($atts)
{
    $atts = shortcode_atts(array(
        'title' => ''
    ), $atts, 'user_login_check');
    if (!is_user_logged_in()) {
        return "Log in to see details";
    }
    return $atts['title'];
}
add_shortcode('user_login_check', 'user_login_check');

function get_water_schedule_title()
{
    ob_start();
    $postID =  get_the_ID();
    $rows = get_field('mp_watering_schedule');
    if ($rows) {
        foreach ($rows as $row) {
            if (!$row['answer']) {
                echo 'Your next water schedule is ' . date('F j, Y', strtotime($row['date'])) . '.';
                break;
            }
        }
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('get_water_schedule_title', 'get_water_schedule_title');

function get_fertilizing_schedule_title()
{
    ob_start();
    $postID =  get_the_ID();
    $rows = get_field('mp_fertilizing_schedule');
    if ($rows) {
        foreach ($rows as $row) {
            if (!$row['answer']) {
                echo 'Your next fertilizing schedule is ' . date('F j, Y', strtotime($row['date'])) . '.';
                break;
            }
        }
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('get_fertilizing_schedule_title', 'get_fertilizing_schedule_title');

function get_frequent_pruning_schedule_title()
{
    ob_start();
    $postID =  get_the_ID();
    $rows = get_field('frequent_pruning_schedule');
    if ($rows) {
        foreach ($rows as $row) {
            if (!$row['answer']) {
                echo 'Your next frequent pruning schedule is ' . date('F j, Y', strtotime($row['date'])) . '.';
                break;
            }
        }
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('get_frequent_pruning_schedule_title', 'get_frequent_pruning_schedule_title');

function get_my_plant_data($atts)
{
    $atts = shortcode_atts(array(
        'type' => 'my_plant',
        'name' => ''
    ), $atts, 'get_my_plant_data');
    if ($atts['type'] == 'plant') {
        $my_plantID = get_the_ID();
        $plant_id = get_field('plant', $my_plantID);
    } else {
        $plant_id = get_the_ID();
    }
    ob_start();
    $result = get_field($atts['name'], $plant_id);
    if (is_array($result)) {
        echo "<ul>";
        foreach ($result as $res) {
            echo "<li>" . $res . "</li>";
        }
        echo "</ul>";
    } else {
        echo $result;
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('get_my_plant_data', 'get_my_plant_data');

function auth_full_name()
{
    $user = wp_get_current_user();
    return $user->first_name . " " . $user->last_name;
}
add_shortcode('auth_full_name', 'auth_full_name');
function logout_url()
{
    return wp_logout_url('/');
}
add_shortcode('logout_url', 'logout_url');

function available_to_manage_check()
{
    $user = wp_get_current_user();
    if (!is_user_logged_in()) {
        return 0;
    }
    $plantID = get_the_ID();
    return (get_field('available_to_manage', $plantID)) ? get_field('available_to_manage', $plantID) : 0;
}
add_shortcode('available_to_manage_check', 'available_to_manage_check');

function add_any_plant()
{
    if (count_user_posts(get_current_user_id(), "my-plant")) {
        return 1;
    } else {
        return 0;
    }
}
add_shortcode('add_any_plant', 'add_any_plant');

function weather_city()
{
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'weather',
        'post_status' => 'publish',
        'orderby' => 'title',
        'order'   => 'ASC',
        'meta_query' => array(
            'province' => array(
                'key' => 'province',
                'value' => 'ON',
                'compare' => '='
            )
        ),
    );
    $cityPosts = new WP_Query($args);
    ob_start();
    $result = array();
    if ($cityPosts->have_posts()) {
        while ($cityPosts->have_posts()) {
            $cityPosts->the_post();
            echo get_the_title() . "|" . get_the_ID() . "\n";
        }
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('weather_city', 'weather_city');
?>