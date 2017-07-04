<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

include(locate_template('function-include/bme_actions.php'));
include(locate_template('function-include/bme_override.php'));
include(locate_template('function-include/bme_filters.php'));

include (locate_template('function-include/widget/bme_recent_news.php'));
include (locate_template('function-include/widget/bme_recent_events.php'));

include (locate_template('function-include/shortcode/bme_display_events.php'));
include (locate_template('function-include/shortcode/bme_display.php'));
include (locate_template('function-include/shortcode/bme_students.php'));




