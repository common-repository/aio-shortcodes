<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  


// Add shortcode support to native WP elements
add_filter('the_title', 'do_shortcode');
add_filter('single_post_title', 'do_shortcode');
add_filter('wp_title', 'do_shortcode');
add_filter('the_excerpt', 'do_shortcode');
