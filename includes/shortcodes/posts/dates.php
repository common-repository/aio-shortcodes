<?php

/**
 * Shortcode: [aio_post_date]
 * Description: Displays the publication date of the specified post.
 * Example usage: [aio_post_date id="456" format="F j, Y"]
 */

function aiosc_post_date_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,            // Default value is null
            'format' => 'F j, Y',    // Default date format
        ),
        $atts,
        'aio_post_date'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $page_id = get_the_ID();
    } else {
        // Use the specified page ID
        $page_id = absint($atts['id']);
    }

    $page_date = get_the_date($atts['format'], $page_id);

    return esc_html($page_date);
}
add_shortcode('aio_post_date', 'aiosc_post_date_shortcode');

/**
 * Shortcode: [aio_post_date_updated]
 * Description: Displays the last modified date of the specified post.
 * Example usage: [aio_post_date_updated id="456" format="F j, Y"]
 */

function aiosc_post_date_updated_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,            // Default value is null
            'format' => 'F j, Y',    // Default date format
        ),
        $atts,
        'aio_post_date_updated'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $page_id = get_the_ID();
    } else {
        // Use the specified page ID
        $page_id = absint($atts['id']);
    }

    $page_date_updated = get_the_modified_date($atts['format'], $page_id);

    return esc_html($page_date_updated);
}
add_shortcode('aio_post_date_updated', 'aiosc_post_date_updated_shortcode');
