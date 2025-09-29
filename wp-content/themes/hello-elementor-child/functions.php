<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')) :
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('child_theme_configurator_css')) :
    function child_theme_configurator_css()
    {
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer'));
        wp_enqueue_style('chld_thm_cfg_child_theme', trailingslashit(get_stylesheet_directory_uri()) . 'assets/css/theme.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer'));
        wp_enqueue_style('chld_thm_cfg_child_vendor', trailingslashit(get_stylesheet_directory_uri()) . 'assets/css/bundle.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);

if (!function_exists('child_theme_configurator_js')) :
    function child_theme_configurator_js()
    {
        wp_enqueue_script('chld_thm_cfg_child_js', trailingslashit(get_stylesheet_directory_uri()) . 'assets/js/bundle.js', array(), '1.0.0', true);
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_js', 11);
function w3reign_svg_mime_type($mimes = array())
{
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'w3reign_svg_mime_type');

//Custom shortcode Post Type
//require_once dirname( __FILE__ ) . '/custom_shortcodes/main-header-container.php';
require_once dirname(__FILE__) . '/custom_shortcodes/dashboard_tableList.php';
require_once dirname(__FILE__) . '/custom_shortcodes/dashboard_view.php';
require_once dirname(__FILE__) . '/custom_shortcodes/plant-timeline-view/plant_timeline_view.php';
require_once dirname(__FILE__) . '/custom_shortcodes/plant_articles.php';
// END ENQUEUE PARENT ACTION

// ...existing code...
foreach (glob(get_stylesheet_directory() . '/snippets/*.php') as $file) {
    require_once $file;
}
 // ...existing code...
