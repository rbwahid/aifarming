<?php
//Shortcode to output custom PHP in Elementor
function plantArticles($atts)
{ ?>

<?php if( have_rows('plant_articles') ): ?>
    <div class="plant-artical-list">
    <?php while( have_rows('plant_articles') ): the_row(); 
        $article_id = get_sub_field('article');
        ?>
        <div class="plant-artical-card">
            <div class="circle-div"></div>
            <div class="plant-artical-content">
                <h5 class="plant-artical-title"><?php echo get_the_title($article_id); ?></h5>
                <p class="plant-artical-shrot-text">
                	<?php echo wp_trim_words( get_the_content('', false, $article_id), 20, '...' ); ?>
		 </p>
                <a href="<?php echo get_permalink($article_id); ?>" class="learn-more-btn">Know more</a>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
<?php endif; ?>
<?php }
add_shortcode('plant-articles', 'plantArticles');
?>