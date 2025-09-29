<?php
//Shortcode to output custom PHP in Elementor
function dashboardTableLists($atts)
{ ?>

<div class="main-content-view">
	<div class="container-fluid">
		<div class="">
			<h1 class="page-title">My plant lists</h1>
		</div>
		<div class="plant-card-header">
			<div class="d-flex align-items-center justify-content-between">
				<div class="tab-content">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/list_icon.png" alt="icon" class="img-fluid">List</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="board-tab" data-bs-toggle="tab" data-bs-target="#board" type="button" role="tab" aria-controls="board" aria-selected="false"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/board-icon.png" alt="icon" class="img-fluid">Board</button>
						</li>
					</ul>
				</div>
				<div class="filter-categories">
					<?php /*
					<a class="search-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/search-icon.png" alt="search" class="img-fluid"></a>
					<a class="filter-dropdown-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/eye-active-icon.png" alt="showhide" class="img-fluid"><span>Show/Hide Filed</span></a>
					<a class="filter-dropdown-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/calender-active-icon.png" alt="calender" class="img-fluid"><span>This month</span></a>
					<a class="filter-dropdown-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/category-active-icon.png" alt="category" class="img-fluid"><span>Category</span></a>
					<a class="filter-dropdown-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/active-status-icon.png" alt="status" class="img-fluid"><span>Status</span></a>
					<div class="divider">
						<div class="line"></div>
					</div>
					*/ ?>
					<a href="/plants" class="add-item-btn filter-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/add-icon.svg" alt="add" class="img-fluid"><span>Add new</span></a>
				</div>
			</div>
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
											<!-- <th scope="col">Category</th> -->
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
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="board" role="tabpanel" aria-labelledby="board-tab">
				<div class="plant-board-view-list">
					<!-- Card Item -->
					<?php while ($query->have_posts()) : $query->the_post(); ?>
					<div class="plant-card-col">
						<div class="card h-100">
						<?php if (has_post_thumbnail()) : ?>
							<img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" alt="plant-img" class="img-fluid">
							<?php else:
								$plantID = get_field('plant');
								$featured_img_url = get_the_post_thumbnail_url($plantID, 'full');
							?>
							<img src="<?php echo $featured_img_url; ?>" alt="<?php the_title(); ?>" alt="plant-img" class="img-fluid">
							<?php endif; ?>
							<div class="card-body">
								<div class="plant-card-header">
									<h5 class="card-title"><?php the_title(); ?></h5>
									<span class="badge bg-warning">Needs attention</span>
								</div>

								<ul class="plant-info-list list-unstyled mt-3">
									<?php /*
									<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-card-icon.png" alt="card-icon" class="img-fluid"> ST365015</li>
									<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-category-icon.png" alt="card-icon" class="img-fluid"> Seasonal</li>
									*/ ?>
									<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/progress-icon.png" alt="card-icon" class="img-fluid"> 20% progress</li>
									<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/place-icon.png" alt="card-icon" class="img-fluid"> <?php echo get_field('plant_site'); ?></li>
								</ul>
								<div class="plant-card-footer d-flex justify-content-between align-items-center">
									<a href="<?php echo get_permalink(); ?>" class="btn plant-details-btn w-100"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/eye-icon.svg" alt="card-icon" class="img-fluid"> View details</a>
									<?php /*
									<a href="#" class="action-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/action-icon.png" alt="action-icon" class="img-fluid"></a> */ ?>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
				<?php /*
				<div class="list-pagination">
					<div class="row">
						<div class="col-lg-6 col-12"></div>
						<div class="col-lg-6 col-12">
							<div class="pagination-block">
								<div class="page-item disabled">
									<a href="javascript:void(0);" class="page-link" id="page-prev">Previous</a>
								</div>
								<span id="page-num" class="pagination">
									<div class="page-item active"><a class="page-link clickPageNumber" href="javascript:void(0);">1</a></div>
									<div class="page-item"><a class="page-link clickPageNumber" href="javascript:void(0);">2</a></div>
								</span>
								<div class="page-item">
									<a href="javascript:void(0);" class="page-link" id="page-next">Next</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				*/ ?>
			</div>
		</div>
<?php
wp_reset_postdata();
endif;
?>
	</div>
</div>

<?php }
add_shortcode('dashboard-table-list', 'dashboardTableLists');
?>