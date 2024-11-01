<?php

/**
 * Shortcode: [aio_spacer]
 * Description: Adds a space/divider with a specified height.
 * Example usage: [aio_spacer] or [aio_spacer height='30px']
 */

function aiosc_spacer_shortcode($atts) {
    // Define default attributes and merge with user attributes
    $atts = shortcode_atts(
        array(
            'height' => '20px', // Default height of the space
        ),
        $atts,
        'aio_spacer'
    );

    // Sanitize the height attribute
    $height = esc_attr($atts['height']);

    // Generate and return the HTML for the spacer
    return '<div style="height:' . $height . ';"></div>';
}

// Register the shortcode
add_shortcode('aio_spacer', 'aiosc_spacer_shortcode');
