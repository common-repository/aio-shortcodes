<?php
/**
 * Shortcode: [aio_page_featured_image]
 * Description: Displays the featured image of the specified page without dimension limits.
 * Example usage: [aio_page_featured_image id="456" size="thumbnail" link="yes" new_window="yes"]
 */

function aiosc_page_featured_image_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,           // Default value is null
            'size' => 'thumbnail',  // Default image size
            'link' => 'no',      // Default link value is 'no'
            'new_window' => 'no', // Default new window value is 'no'
        ),
        $atts,
        'aio_page_featured_image'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current page ID
        $page_id = get_the_ID();
    } else {
        // Use the specified page ID
        $page_id = absint($atts['id']);
    }

    // Validate the size attribute to use only allowed sizes (thumbnail, medium, large)
    $allowed_sizes = array('thumbnail', 'medium', 'large');
    $atts['size'] = in_array($atts['size'], $allowed_sizes) ? $atts['size'] : 'thumbnail';

    $thumbnail_url = get_the_post_thumbnail_url($page_id, $atts['size']);

    if ($thumbnail_url) {
        // Check if link attribute is set to yes
        if ($atts['link'] === 'yes') {
            $target = $atts['new_window'] === 'yes' ? ' target="_blank"' : '';
            return '<a href="' . esc_url(get_permalink($page_id)) . '"' . $target . '><img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title($page_id)) . '"></a>';
        } else {
            return '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title($page_id)) . '">';
        }
    } else {
        return ''; // No featured image found
    }
}
add_shortcode('aio_page_featured_image', 'aiosc_page_featured_image_shortcode');
