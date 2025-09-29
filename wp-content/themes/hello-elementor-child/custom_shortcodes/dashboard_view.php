<?php
//Shortcode to output custom PHP in Elementor
function dasboardView($atts)
{ ?>

    <div class="main-content-view">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between title-header">
                <h1 class="page-title m-0">Overview</h1>
                <div class="filter-categories"><a class="add-item-btn filter-btn" href="/available-plants"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/add-icon.svg" alt="add" class="img-fluid"><span>Add new</span></a></div>
            </div>
            <div class="dashboard-overview-block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboad-car-view">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start gap-10">
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/total-plants-planted-icon.png" alt="card-icon" class="img-fluid"></div>
                                                    <div class="d-flex align-items-center justify-content-between card-title">Total Plants Planted</div>
                                                </div>
                                                <div class="flex-fill">
                                                    <h4 class="fw-semibold"><?php echo count_user_posts( get_current_user_id() , "my-plant" ); ?></h4>
						    <?php /* ?>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div><span class="badge bg-success">+22%</span></div>
                                                        <a href="javascript:void(0);" class="fs-12">Last 30 days <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dropdown-icon-old.png" alt="dropsown-icon" class="img-fluid"></a>
                                                    </div>
						    <?php */ ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start gap-10">
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/ready-for-harvest.png" alt="card-icon" class="img-fluid"></div>
                                                    <div class="d-flex align-items-center justify-content-between card-title">Ready for Harvest</div>
                                                </div>
                                                <div class="flex-fill">
						<?php $ready_for_harvest = new WP_Query( array( 'post_type' => 'my-plant', 'meta_key' => 'ready_for_harvest', 'meta_value' => '1' ) ); ?>
                                                    <h4 class="fw-semibold"><?php echo $ready_for_harvest->found_posts; ?></h4>
                                                    <?php /* ?>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div><span class="badge bg-danger">-08%</span></div>
                                                        <a href="javascript:void(0);" class="fs-12">Last 30 days <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dropdown-icon-old.png" alt="dropsown-icon" class="img-fluid"></a>
                                                    </div>
						    <?php */ ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start gap-10">
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plants-monitored-icon.png" alt="card-icon" class="img-fluid"></div>
                                                    <div class="d-flex align-items-center justify-content-between card-title">Plants Monitored</div>
                                                </div>
                                                <div class="flex-fill">
                                                    <?php $plants_monitored = new WP_Query( array( 'post_type' => 'my-plant', 'meta_key' => 'plants_monitored', 'meta_value' => '1' ) ); ?>
                                                    <h4 class="fw-semibold"><?php echo $plants_monitored->found_posts; ?></h4>
                                                    <?php /* ?>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div><span class="badge bg-success">+22%</span></div>
                                                        <a href="javascript:void(0);" class="fs-12">Last 30 days <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dropdown-icon-old.png" alt="dropsown-icon" class="img-fluid"></a>
                                                    </div>
						    <?php */ ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
				    <?php /*
                                    <div class="col-md-3">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start gap-10">
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-progress-icon.png" alt="card-icon" class="img-fluid"></div>
                                                    <div class="d-flex align-items-center justify-content-between card-title">Plant Progress</div>
                                                </div>
                                                <div class="flex-fill">
                                                    <h4 class="fw-semibold">30% <span>Growing</span></h4>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div><span class="badge bg-danger">-08%</span></div>
                                                        <a href="javascript:void(0);" class="fs-12">Last 30 days <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dropdown-icon-old.png" alt="dropsown-icon" class="img-fluid"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				    */ ?>
                                </div>
                            </div>
                        </div>
<?php 
	$userID = get_current_user_id();
	$city = get_field('user_city', 'user_'.  $userID);
	$weather_id = post_exists( $city,'','','weather' );
	$updateDate = strtotime(get_field('last_update',$weather_id));
	if($weather_id){ 
		$temparature = str_replace(" C", "", get_field('temperature',$weather_id));
?>

                        <div class="weather-view card">
                            <div class="weather-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="weather-info">
                                        <div class="d-flex align-items-satrt gap-3">
                                            <div>
                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/weather.png" alt="add" class="img-fluid">
                                            </div>
                                            <div>
                                                <div class="d-flex">
                                                    <span class="number-text"><?php echo $temparature; ?></span> <span class="weather-degree">C</span>
<?php /*
                                                    <div class="divider">
                                                        <div class="line"></div>
                                                    </div><span class="weather-degree">F</span>
*/ ?>
                                                </div>
                                                <h5 class="weather-status"><?php echo get_field('condition',$weather_id); ?></h5>
                                            </div>
                                            <div class="weather-status-info">
                                                <p>Humidity <span><?php echo get_field('humidity',$weather_id); ?></span> </p>
                                                <p>Wind <span><?php echo get_field('wind',$weather_id); ?></span> </p>                                                
						<p>Pressure <span><?php echo get_field('pressure',$weather_id); ?></span> </p>                                                
						<p>Dewpoint <span><?php echo get_field('dewpoint',$weather_id); ?></span> </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="weather-date">
                                        <h5>Weather</h5>
                                        <p class="weather-date"><?php echo date('j F, Y', $updateDate); ?></p>
                                        <p class="weather-date"><?php echo date('l h:i A', $updateDate); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="line-2"></div>
                            <div class="weather-notification">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/notification-bar.png" alt="notification" class="img-fluid">
                                <p><?php echo get_field('forecast_summary',$weather_id); ?></p>
                            </div>
                        </div>
<?php } ?>
                    </div>

<?php /*
                    <div class="col-md-4">
                    <div class="r-card card">
                        <div class="recommendations d-flex justify-content-between"><h4 class="rtitle">Recommendations</h4><a href="#" class="all-btn">See all <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/blue-icon.svg" alt="arrow" class="img-fluid"></a></div>
                        <div class="recommendations-list">
                            <div class="recommendations-list-card">
                                <div class="nlist">
                                    <h5>Step by step tomato storing</h5>
                                    <span>Perfect weather for planting. It’s so important as...</span>
                                </div>
                                <div class="text-right"><a href="#" class="more-btn">Know more <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/black-icon.svg" alt="arrow" class="img-fluid"></a></div>
                            </div>
                            <div class="recommendations-list-card">
                                <div class="nlist">
                                    <h5>Step by step tomato storing</h5>
                                    <span>Perfect weather for planting. It’s so important as...</span>
                                </div>
                                <div class="text-right"><a href="#" class="more-btn">Know more <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/black-icon.svg" alt="arrow" class="img-fluid"></a></div>
                            </div>
                            <div class="recommendations-list-card">
                                <div class="nlist">
                                    <h5>Step by step tomato storing</h5>
                                    <span>Perfect weather for planting. It’s so important as...</span>
                                </div>
                                <div class="text-right"><a href="#" class="more-btn">Know more <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/black-icon.svg" alt="arrow" class="img-fluid"></a></div>
                            </div>
                            <div class="recommendations-list-card">
                                <div class="nlist">
                                    <h5>Step by step tomato storing</h5>
                                    <span>Perfect weather for planting. It’s so important as...</span>
                                </div>
                                <div class="text-right"><a href="#" class="more-btn">Know more <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/black-icon.svg" alt="arrow" class="img-fluid"></a></div>
                            </div>

                        </div>
                    </div>

                    </div>
*/ ?>

                </div>
            </div>

            <div class="">
                <h1 class="page-title">My plant lists</h1>
            </div>
<?php
// Get current user ID
$current_user_id = get_current_user_id();

// Query for "my-plant" posts authored by the current user
$args = array(
    'post_type'      => 'my-plant',
    'author'         => $current_user_id,
    'posts_per_page' => -1, // Get all posts
);

$query = new WP_Query($args);

if ($query->have_posts()) :
?>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
                    <div class="table-content">
                        <div class="table-list">
                            <div class="table-responsive">
                                <div class="table text-nowrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th scope="col">Name & ID</th>
                                                <?php /* <th scope="col">Category</th> */ ?>
                                                <th scope="col">Health status</th>
                                                <th scope="col">Progress</th>
                                                <th scope="col">Zone</th>
                                                <th scope="col">Planting date</th>
                                                <th scope="col">Day Since Planted</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					<?php while ($query->have_posts()) : $query->the_post(); ?>
					<?php 
					$get_plant_id = get_field('plant');
					$ai_information = get_field('ai_information', $get_plant_id);
					if ($ai_information && is_array($ai_information)) {
						$last_row = end($ai_information); // Get the last row
						$milestone_stage = isset($last_row['milestone_stage']) ? $last_row['milestone_stage'] : '';
					}
					$current_milestone_stage = get_field('current_milestone_stage');
					$current_milestone_stage = ($current_milestone_stage > 0) ? $current_milestone_stage - 1 : $current_milestone_stage;
					$per_stage_percentage = 100/$milestone_stage;
					$current_percentage = $per_stage_percentage * $current_milestone_stage;   
					?>
                                            <tr class="todo-box">
                                                <td class="task-checkbox">
                                                    <!-- <input class="form-check-input" type="checkbox" value="" aria-label="..."> -->
						   <?php if (has_post_thumbnail()) : ?>
                        		     	    		<img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" alt="plant-img" class="img-fluid">
                        			    	   <?php else:
$plantID = get_field('plant');
$featured_img_url = get_the_post_thumbnail_url($plantID, 'full');
							?>
                                                         <img src="<?php echo $featured_img_url; ?>" alt="<?php the_title(); ?>" alt="plant-img" class="img-fluid">
						   <?php endif; ?>
                                                    <div class="plant-details">
                                                        <p class="plant-name"><?php the_title(); ?></p><span class="plant-id"></span>
                                                    </div>
                                                </td>
                                                <!-- <td> <span class="fw-medium">Seasonal</span> </td> -->
                                                <td> <span class="badge bg-<?php echo get_field('health_status'); ?>"><?php echo get_field('health_status_text'); ?></span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-<?php echo $current_percentage; ?>" role="progressbar" aria-valuenow="<?php echo $current_percentage; ?>" aria-valuemin="0" aria-valuemax="100">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: <?php echo $current_percentage; ?>%"></div>
							</div>
							<div class="ms-2 percent"><?php echo $current_percentage; ?>%</div>
                                                    </div>
                                                </td>
                                                <td> <span><?php echo get_field('plant_site'); ?></span> </td>
                                                <td> <span><?php echo date('j F, Y', strtotime(get_field('start_date'))); ?></span> </td>
<?php 
$starting_date = date('Y-m-d', strtotime(get_field('start_date')));
$today = date('Y-m-d');

$start_date_obj = new DateTime($starting_date);
$today_date_obj = new DateTime($today);

$interval = $start_date_obj->diff($today_date_obj);
$days_count = $interval->days;
$totalDays = $days_count + (get_field('planted_ground')) ?? 0;
                                                 
?>
                                                <td> <span><?php echo ($totalDays > 1) ? $totalDays." Days": $totalDays." Day"; ?></span> </td>
                                                <td>
                                                    <div> <a href="<?php echo get_permalink(); ?>"><span>View</span></a> </div>
                                                </td>
                                            </tr>
<?php endwhile; ?>
					<?php /*
                                            <tr class="todo-box">
                                                <td class="task-checkbox"><!--<input class="form-check-input" type="checkbox" value="" aria-label="...">--> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Pepper tree.png" alt="plant-img" class="img-fluid">
                                                    <div class="plant-details">
                                                        <p class="plant-name">Pepper tree</p><span class="plant-id">ST365015</span>
                                                    </div>
                                                </td>

                                                <td> <span class="fw-medium">Unseasonal</span> </td>
                                                <td> <span class="badge bg-success">Healthy</span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-50" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 30%"></div>
                                                        </div>
                                                        <div class="ms-2 percent">30%</div>
                                                    </div>
                                                </td>
                                                <td> <span>Backyard Patch</span> </td>
                                                <td> <span>Aug 8, 2024</span> </td>
                                                <td> <span>12 Days</span> </td>
                                                <td>
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"> </div>
                                                </td>
                                            </tr>

                                            <tr class="todo-box">
                                                <td class="task-checkbox"><input class="form-check-input" type="checkbox" value="" aria-label="..."> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Flower.png" alt="plant-img" class="img-fluid">
                                                    <div class="plant-details">
                                                        <p class="plant-name">Flower</p><span class="plant-id">ST365015</span>
                                                    </div>
                                                </td>

                                                <td> <span class="fw-medium">Regular</span> </td>
                                                <td> <span class="badge bg-danger">High risk</span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-50" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 40%"></div>
                                                        </div>
                                                        <div class="ms-2 percent">40%</div>
                                                    </div>
                                                </td>
                                                <td> <span>Rooftop Garden</span> </td>
                                                <td> <span>Aug 9, 2024</span> </td>
                                                <td> <span>16 Days</span> </td>
                                                <td>
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"> </div>
                                                </td>
                                            </tr>

                                            <tr class="todo-box">
                                                <td class="task-checkbox"><input class="form-check-input" type="checkbox" value="" aria-label="..."> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Flower1.png" alt="plant-img" class="img-fluid">
                                                    <div class="plant-details">
                                                        <p class="plant-name">Tomato tree</p><span class="plant-id">ST365015</span>
                                                    </div>
                                                </td>

                                                <td> <span class="fw-medium">Regular</span> </td>
                                                <td> <span class="badge bg-warning">Needs attention</span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-50" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 60%"></div>
                                                        </div>
                                                        <div class="ms-2 percent">60%</div>
                                                    </div>
                                                </td>
                                                <td> <span>Community Garden Plot</span> </td>
                                                <td> <span>Aug 10, 2024</span> </td>
                                                <td> <span>09 Days</span> </td>
                                                <td>
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"> </div>
                                                </td>
                                            </tr>

                                            <tr class="todo-box">
                                                <td class="task-checkbox"><input class="form-check-input" type="checkbox" value="" aria-label="..."> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Watermelon1.png" alt="plant-img" class="img-fluid">
                                                    <div class="plant-details">
                                                        <p class="plant-name">Watermelon tree</p><span class="plant-id">ST365015</span>
                                                    </div>
                                                </td>

                                                <td> <span class="fw-medium">Unseasonal</span> </td>
                                                <td> <span class="badge bg-success">Healthy</span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-50" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 55%"></div>
                                                        </div>
                                                        <div class="ms-2 percent">55%</div>
                                                    </div>
                                                </td>
                                                <td> <span>Sidewalk Border</span> </td>
                                                <td> <span>Aug 11, 2024</span> </td>
                                                <td> <span>22 Days</span> </td>
                                                <td>
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"> </div>
                                                </td>
                                            </tr>

                                            <tr class="todo-box">
                                                <td class="task-checkbox"><input class="form-check-input" type="checkbox" value="" aria-label="..."> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Potato_tree.png" alt="plant-img" class="img-fluid">
                                                    <div class="plant-details">
                                                        <p class="plant-name">Potato tree</p><span class="plant-id">ST365015</span>
                                                    </div>
                                                </td>

                                                <td> <span class="fw-medium">Seasonal</span> </td>
                                                <td> <span class="badge bg-danger">High risk</span> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress progress-animate progress-xs w-50" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 25%"></div>
                                                        </div>
                                                        <div class="ms-2 percent">25%</div>
                                                    </div>
                                                </td>
                                                <td> <span>Courtyard Centerpiece</span> </td>
                                                <td> <span>Aug 14, 2024</span> </td>
                                                <td> <span>26 Days</span> </td>
                                                <td>
                                                    <div> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"> </div>
                                                </td>
                                            </tr>
*/ ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="board" role="tabpanel" aria-labelledby="board-tab">...</div>
            </div>

<?php
    wp_reset_postdata();
else :
    echo "<p>No plants found.</p>";
endif;
?>
        </div>
    </div>

<?php }
add_shortcode('dashboard-view', 'dasboardView');
?>