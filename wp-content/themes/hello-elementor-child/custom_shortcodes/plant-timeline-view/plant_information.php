<div class="plant-all-info">
    <div class="row">
        <div class="col-md-6">
            <div class="title">Preferred plant site</div>
            <div class="info-card">
                <div class="info-details">
                    <p class="info-text">Pick a site you have.</p>
                    <div class="user-info"><?php echo get_field('indoor_or_outdoor'); ?></div>

                    <p class="info-text">Specific</p>
                    <div class="user-info"><?php echo get_field('plant_site'); ?></div>
                </div>
            </div>
            <div class="title">Pot or ground info</div>
            <div class="info-card">
                <div class="info-details">
                    <p class="info-text">Is it potted or planted in the ground?</p>
                    <div class="user-info"><?php echo get_field('pot_or_ground'); ?></div>
                </div>
            </div>

        </div>
        <div class="col-md-6">
        <div class="title">Plant basic info</div>
            <div class="info-card">
                <div class="info-details">
                    <p class="info-text">What is your pot type?</p>
                    <div class="user-info"><?php echo get_field('pot_type'); ?></div>

                    <p class="info-text">What is your pot size? (in cm)</p>
                    <div class="user-info"><?php echo get_field('pot_size'); ?> cm</div>

                    <p class="info-text">When was this last repotted?</p>
					<?php 
					$field = get_field_object('field_66601f317ce45');
					$value = get_field('field_66601f317ce45');
					$label = $field['choices'][ $value ];
					?>
                    <div class="user-info"><?php echo $label; ?></div>

                    <p class="info-text">Is it drainage?</p>
                    <div class="user-info"><?php echo get_field('is_it_drainage'); ?></div>

                    <p class="info-text">Soil type</p>
                    <div class="user-info"><?php echo get_field('soil_type'); ?></div>

                    <p class="info-text">Plant name</p>
                    <div class="user-info"><?php echo get_the_title(); ?></div>

                </div>
            </div>
        </div>
    </div>
</div>