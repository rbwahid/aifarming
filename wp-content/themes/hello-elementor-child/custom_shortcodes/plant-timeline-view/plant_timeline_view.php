<?php
//Shortcode to output custom PHP in Elementor
function plantTimeLineView($atts)
{ 
$plant_id = get_field('plant', get_the_ID());
$image_url = get_the_post_thumbnail_url( $plant_id, 'full' );
$scientific_name = get_field('scientific_name', $plant_id);
?>

    <div>
        <div class="plant_details_11">
            <img src="<?php echo $image_url; ?>" class="plant-img" alt="plant-img" class="img-fluid" />
            <div class="d-flex align-items-center C-gap-20">
                <h2 class="plant-name"><?php echo get_the_title(); ?></h2>
		<?php /*
                <span class="badge bg-success">Healthy</span>
                <span class="badge bg-warning">Needs attention</span>
                <span class="badge bg-danger">High risk</span>
		*/ ?> 
            </div>
            <div class="plant-code"><?php echo $scientific_name; ?></div>
        </div>
        <div class="plant-detalis-accordion">
            <ul class="nav nav-tabs" id="plantTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="plant-timeline-tab" data-bs-toggle="tab" data-bs-target="#plant-timeline" type="button" role="tab" aria-controls="plant-timeline" aria-selected="true">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-timeline-inactive-icon.png" class="inactive-icon" alt="inactive-icon" class="img-fluid" />
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-timeline-active-icon.png" class="active-icon" alt="active-icon" class="img-fluid" />
                        plant-timeline
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="plant-information-tab" data-bs-toggle="tab" data-bs-target="#plant-information" type="button" role="tab" aria-controls="plant-information" aria-selected="false">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-info-inactive-icon.png" class="inactive-icon" alt="inactive-icon" class="img-fluid" />
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-info-active-icon.png" class="active-icon" alt="active-icon" class="img-fluid" />
                        plant-information
                    </button>
                </li>

            </ul>
            <div class="tab-content" id="plantTabContent">
                <div class="tab-pane fade show active" id="plant-timeline" role="tabpanel" aria-labelledby="plant-timeline-tab">
                    <h3 class="tab-content-title">Plant timeline</h3>
                    <?php
                    require("plant_timeline.php");
                    ?>
                </div>
                <div class="tab-pane fade" id="plant-information" role="tabpanel" aria-labelledby="plant-information-tab">
                    <h3 class="tab-content-title">Plant Information</h3>
                    <?php
                    require("plant_information.php");
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php }
add_shortcode('plant-timeline-view', 'plantTimeLineView');
?>