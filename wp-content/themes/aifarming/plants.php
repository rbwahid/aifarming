<?php 
/* Template Name: Plant Page */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
global $sklentr_variable;
$is_elementor_theme_exist = function_exists( 'elementor_theme_do_location' );
if ( is_singular() ) {
	if ( ! $is_elementor_theme_exist || ! elementor_theme_do_location( 'single' ) ) {
		get_template_part( 'template-parts/single' );
	}
} elseif ( is_archive() || is_home() ) {
	if ( ! $is_elementor_theme_exist || ! elementor_theme_do_location( 'archive' ) ) {
		get_template_part( 'template-parts/archive' );
	}
} elseif ( is_search() ) {
	if ( ! $is_elementor_theme_exist || ! elementor_theme_do_location( 'archive' ) ) {
		get_template_part( 'template-parts/search' );
	}
} else {
	if ( ! $is_elementor_theme_exist || ! elementor_theme_do_location( 'single' ) ) {
		get_template_part( 'template-parts/404' );
	}
}
?>
<script>
	jQuery('document').ready(function($){
		$('body').on("click", ".plant-selected-link", function(e){
			plantThis = $(this);
			var plant = $(this).data('plant-id');   
			$.ajax({
					 type : "GET",
					 dataType : "json",
					 url : "/wp-admin/admin-ajax.php",
					 data : {action: "get_data",plant: plant},
					 success: function(response) {

							if(response == 1){
								msg = '<div style="border: 3px solid #297243;padding: 5px;margin-top:10px;"><?php echo $sklentr_variable['successful_plant_select_msg']; ?></div>';
								plantThis.find('.elementor-widget-container').append(msg);
								window.location.href = "<?php echo $sklentr_variable['my_plant_url']; ?>";
							}
							else if(response == 2){
								msg = '<div style="border: 3px solid #cf2e2e;padding: 5px;margin-top:10px;"><?php echo $sklentr_variable['something_wrong_msg']; ?></div>';
								plantThis.find('.elementor-widget-container').append(msg);
								window.location.href = "<?php echo $sklentr_variable['plant_url']; ?>";
							}else{
								msg = '<div style="border: 3px solid #cf2e2e;padding: 5px;margin-top:10px;"><?php echo $sklentr_variable['login_first_msg']; ?></div>';
								plantThis.find('.elementor-widget-container').append(msg);
								window.location.href = "<?php echo $sklentr_variable['login_url']; ?>";
							}
						}
				});   

		});
	})
</script>
<style>
	.plant-selected-link{
		cursor: pointer;
	}
</style>
<?php
get_footer();
