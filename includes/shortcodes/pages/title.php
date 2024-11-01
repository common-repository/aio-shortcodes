<?php

/**
 * Shortcode: [aio_page_title]
 * Description: Displays the title of the specified page.
 * Example usage: [aio_page_title id="456" link="yes" new_window="yes"]
 */

function aiosc_page_title_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,           // Default value is null
            'link' => 'no',      // Default value is 'no'
            'new_window' => 'no', // Default value is 'no'
        ),
        $atts,
        'aio_page_title'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current page ID
        $page_id = get_the_ID();
    } else {
        // Use the specified page ID
        $page_id = absint($atts['id']);
    }

    $page_title = get_the_title($page_id);

    if ($atts['link'] === 'yes') {
        $target = $atts['new_window'] === 'yes' ? ' target="_blank"' : '';
        $page_title = '<a href="' . esc_url(get_permalink($page_id)) . '"' . $target . '>' . esc_html($page_title) . '</a>';
    } else {
        $page_title = esc_html($page_title);
    }

    return $page_title;
}
add_shortcode('aio_page_title', 'aiosc_page_title_shortcode');
