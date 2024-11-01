<?php
/**
 * Shortcode: [aio_post_url]
 * Description: Displays the URL of the specified post.
 * Example usage: [aio_post_url id="456" link="yes" class="custom-class" new_window="no"]
 */

function aio_post_url_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,          // Default value is null
            'link' => 'yes',      // Default value is 'yes' (post URL as link)
            'class' => '',         // Default value is an empty string for the class
            'new_window' => 'no', // Default value is 'no' (open link in the same window)
        ),
        $atts,
        'aio_post_url'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $post_id = get_the_ID();
    } else {
        // Use the specified post ID
        $post_id = absint($atts['id']);
    }

    $post_url = get_permalink($post_id);

    if ($atts['link'] === 'yes') {
        // Use the post URL as a link
        $post_url = '<a class="' . esc_attr($atts['class']) . '" href="' . esc_url($post_url) . '" ' . ($atts['new_window'] === 'yes' ? 'target="_blank" rel="noopener noreferrer"' : '') . '>' . esc_html($post_url) . '</a>';
    } else {
        // Display the plain post URL
        $post_url = esc_url($post_url);
    }

    return $post_url;
}
add_shortcode('aio_post_url', 'aio_post_url_shortcode');
