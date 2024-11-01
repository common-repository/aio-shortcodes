<?php
function aio_custom_css_customize_register($wp_customize)
{
    $custom_css_enabled = get_option('aio_custom_css_toggle');
    if ($custom_css_enabled) {
        $wp_customize->add_section('aio_custom_css_section', array(
            'title' => __('AIO Custom CSS', 'aio-shortcodes'),
            'priority' => 200,
        ));

        $wp_customize->add_setting('aio_custom_css', array(
            'type'              => 'option',
            'sanitize_callback' => 'aio_css_sanitize_css',
			'transport'			=> 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Code_Editor_Control( $wp_customize,'aio_custom_css', array(
            'label' => __('Enter your CSS:', 'aio-shortcodes'),
            'section' => 'aio_custom_css_section',
            'code_type' => 'css',
            'settings' => 'aio_custom_css',
           
        )));
    }
}
add_action('customize_register', 'aio_custom_css_customize_register');

/**
 * Strip HTML from our CSS.
 *
 * @since 1.0
 *
 * @param $input Our initial CSS.
 */
function aio_css_sanitize_css( $input ) {
	return strip_tags( $input );
}


//  function aio_custom_css_output() {
//      $custom_css = get_theme_mod('aio_custom_css', '');

//      if (!empty($custom_css)) {
//          echo '<style type="text/css">' . esc_html($custom_css) . '</style>';
//    // sprintf('<style type="text/css">%s</style>', esc_html($custom_css));
//     }
//  }
//  add_action('wp_head', 'aio_custom_css_output');

function aio_custom_css_output()
{
    $customizer_css = get_option('aio_custom_css', '');
    $custom_css_enabled = get_option('aio_custom_css_toggle');

    if (is_single()) {
        // Get post/page/custom-post-specific CSS
        $post_id = get_the_ID();
        $post_css = get_post_meta($post_id, '_aio_custom_css', true);

        $customizer_css .= $customizer_css . ' ' . $post_css;
    }

    if ($custom_css_enabled && !empty($customizer_css)) {
        echo  sprintf('<style type="text/css" id="aio_css_output">%s</style>', esc_html($customizer_css));
    }
}

add_action('wp_head', 'aio_custom_css_output');




/*** 
 * To add functionality to add css-box on each post and pages
 */

// Function to add a meta box for custom CSS in post/page editor
function aio_custom_css_meta_box()
{
    $args = array('public' => true);
    $post_types = get_post_types($args);
    $custom_css_enabled = get_option('aio_custom_css_toggle');

    // If the option is not enabled, don't show the metabox
    if ($custom_css_enabled) {

        foreach ($post_types as $post_type) {
            add_meta_box(
                'aio_custom_css_meta_box',
                __('AIO Custom CSS', 'aio-shortcodes'),
                'aio_custom_css_meta_box_callback',  // funtion
                $post_type,
                'normal',
                'default'
            );
        }
    }
}
add_action('add_meta_boxes', 'aio_custom_css_meta_box');


// Meta box callback function
function aio_custom_css_meta_box_callback($post)
{

    wp_nonce_field('aio_custom_css_nonce', 'aio_custom_css_nonce');
    $custom_css = get_post_meta($post->ID, '_aio_custom_css', true);
?>
    <label for="aio_custom_css"><?php _e('Enter your custom CSS:', 'aio-shortcodes'); ?></label>
    <textarea style="width:100%;height:300px;" id="aio_custom_css" name="_aio_custom_css"><?php echo strip_tags($custom_css); ?></textarea>
<?php

}


// Save custom CSS when post is updated
function aiosc_save_custom_css($post_id)
{
    // Verify nonce before saving
    if (!isset($_POST['aio_custom_css_nonce']) || !wp_verify_nonce($_POST['aio_custom_css_nonce'], 'aio_custom_css_nonce')) {
        return;
    }

    if (isset($_POST['_aio_custom_css']) && $_POST['_aio_custom_css'] !== '') {
        update_post_meta($post_id, '_aio_custom_css', wp_kses_post($_POST['_aio_custom_css']));
    } else {
        delete_post_meta($post_id, '_aio_custom_css');
    }
}
add_action('save_post', 'aiosc_save_custom_css');
