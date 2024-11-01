<?php
/**
 * Shortcode: [aio_post_excerpt]
 * Description: Displays the excerpt of the specified post.
 * Example usage: [aio_post_excerpt id="456" length="50"]
 */

function aiosc_post_excerpt_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,            // Default value is null
            'length' => '',          // Default length is empty (full excerpt)
        ),
        $atts,
        'aio_post_excerpt'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $post_id = get_the_ID();
    } else {
        // Use the specified post ID
        $post_id = absint($atts['id']);
    }

    // Get the post excerpt
    $post_excerpt = get_the_excerpt($post_id);

    // Check if a custom length is specified
    if (!empty($atts['length'])) {
        // Trim the excerpt to the specified length
        $post_excerpt = wp_trim_words($post_excerpt, $atts['length']);
    }

    return wpautop(esc_html($post_excerpt));
}
add_shortcode('aio_post_excerpt', 'aiosc_post_excerpt_shortcode');
