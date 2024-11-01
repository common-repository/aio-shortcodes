<?php
/**
 * Shortcode: [aio_page_url]
 * Description: Displays the URL of the specified page.
 * Example usage: [aio_page_url id="456" link="yes" class="custom-class" new_window="no"]
 */

function aio_page_url_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,          // Default value is null
            'link' => 'yes',      // Default value is 'yes' (page URL as link)
            'class' => '',         // Default value is an empty string for the class
            'new_window' => 'no', // Default value is 'no' (open link in the same window)
        ),
        $atts,
        'aio_page_url'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current page ID
        $page_id = get_the_ID();
    } else {
        // Use the specified page ID
        $page_id = absint($atts['id']);
    }

    $page_url = get_permalink($page_id);

    if ($atts['link'] === 'yes') {
        // Use the page URL as a link
        $page_url = '<a class="' . esc_attr($atts['class']) . '" href="' . esc_url($page_url) . '" ' . ($atts['new_window'] === 'yes' ? 'target="_blank" rel="noopener noreferrer"' : '') . '>' . esc_html($page_url) . '</a>';
    } else {
        // Display the plain page URL
        $page_url = esc_url($page_url);
    }

    return $page_url;
}
add_shortcode('aio_page_url', 'aio_page_url_shortcode');
