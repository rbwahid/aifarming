<?php 
$aiRepeater = get_field('ai_information',$plant_id);
?>
<div class="plantTimeline-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="planting-steps">
                    <div class="accordion" id="accordionExample">
					<?php foreach($aiRepeater as $row){ 
						if($row['type_of_the_section'] == 6){
							$classTitle = "collapsed";
							$classDesc = "";
							$expanded = "false";
							if(get_field('current_milestone_stage') == $row['milestone_stage']){
								$classTitle = "";
								$classDesc = "show";
								$expanded = "true";
							}
					?>
                        <div class="accordion-item milestone-<?php echo $row['milestone_stage']; ?>">
                            <h2 class="accordion-header" id="heading<?php echo $row['milestone_stage']; ?>">
                                <button class="accordion-button <?php echo $classTitle; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $row['milestone_stage']; ?>" aria-expanded="<?php echo $expanded; ?>" aria-controls="collapseOne">
                                    <div class="d-flex align-items-center accordion-title">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/milestone1-img.png" alt="plant-img" class="img-fluid" />
                                        <div class="milestone-info">
                                            <p>Milestone - <?php echo $row['milestone_stage']; ?></p>
                                            <div class="d-flex">
												<?php echo $row['title_of_the_section']; ?> 
												<?php
												if(get_field('current_milestone_stage') < $row['milestone_stage']){
												?>
												<div class="step-lock">lock</div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
							<?php
							if(get_field('current_milestone_stage') >= $row['milestone_stage']){
							?>
                            <div id="collapse<?php echo $row['milestone_stage']; ?>" class="accordion-collapse collapse <?php echo $classDesc; ?>" aria-labelledby="heading<?php echo $row['milestone_stage']; ?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="accordion innerAccordion" id="innerAccordion">
									<?php
									$query = new WP_Query([
										'post_type' => 'plant-history',
										'order' => 'ASC',
										'meta_query' => [
											'relation' => 'AND',
											[
												'key' => 'user_plant',
												'value' => get_the_ID(),
											],
											[
												'key' => 'milestone_stage',
												'value' => $row['milestone_stage'],
											]
										]
									]);

									$posts = $query->get_posts();
									$i = 1;
									$last_stage = 0;
									$count = count($posts);
									$j = 1;
									foreach($posts as $post){
										$title = get_the_title($post->ID);
										$last_stage = get_field('type',$post->ID);
										if(get_field('type',$post->ID) == 6){
											$title = 'Initial Instruction';
										}
										if($j == $count && $classDesc == 'show'){
											$buttonClass = '';
											$buttonAriaExpanded = 'true';
											$contentClass = 'show';
											
										}else{
											$buttonClass = 'collapsed';
											$buttonAriaExpanded = 'false';
											$contentClass = '';
										}
									?>
										<div class="accordion-item">
                                            <h2 class="accordion-header" id="headingPlanting_<?php echo $row['milestone_stage']; ?>_<?php echo $i; ?>">
                                                <button class="accordion-button <?php echo $buttonClass; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePlanting_<?php echo $row['milestone_stage']; ?>_<?php echo $i; ?>" aria-expanded="<?php echo $buttonAriaExpanded; ?>" aria-controls="collapsePlanting_<?php echo $row['milestone_stage']; ?>_<?php echo $i; ?>">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant.svg" alt="icon" class="img-fluid" /> <?php echo $title; ?>
                                                </button>
                                            </h2>
                                            <div id="collapsePlanting_<?php echo $row['milestone_stage']; ?>_<?php echo $i; ?>" class="accordion-collapse collapse <?php echo $contentClass; ?>" aria-labelledby="headingPlanting_<?php echo $row['milestone_stage']; ?>_<?php echo $i; ?>" data-bs-parent="#innerAccordion">
                                                <div class="accordion-body">
                                                    <div>
                                                        <h5 class="title-accordion-11">Instruction</h5>
                                                        <p class="title-accordion-text"><?php echo get_field('description', $post->ID); ?></p>
														<?php 
														$articles = get_field('articles',$post->ID);
														if($articles){
														?>
                                                        <h5 class="title-accordion-11">Article</h5>
														<?php 
														foreach($articles as $article){
														?>
                                                        <p class="title-accordion-text"><a href="<?php echo get_permalink($article);  ?>"><?php echo get_the_title($article) ?></a></p>
														<?php }
														} ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $i++; $j++;
										} ?>
                                    </div>
									<?php
									if(get_field('current_milestone_stage') == $row['milestone_stage']){
										if(get_field('current_milestone_stage') == $row['milestone_stage'] && $last_stage == 8){
										?>
										<a class="unlock-btn active" data-post="<?php $post->ID ?>">Unlock the next milestone</a>
										<?php 
										}else{
										?>
										<a href="javascript:void(0);" class="unlock-btn" style="background: #666;">Unlock the next milestone</a>
									<?php 
										}
									} ?>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
						<?php }
						} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>