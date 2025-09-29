<?php
//Shortcode to output custom PHP in Elementor
function mainHeaderContainer($atts)
{?>

<div class="app-header">
    <div class="main-header-container container-fluid">
        <div class="header-content-left">
            <div><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/plant-icon.png" alt="plant-icon" class="img-fluid">My plant lists</div>
        </div>
        <div class="header-content-right"></div>
    </div>
</div>

<?php }
add_shortcode('main-header-container', 'mainHeaderContainer');
?>