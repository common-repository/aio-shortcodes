<?php
/**
 * Shortcode: [aio_post_category]
 * Description: Displays the categories of the specified post.
 * Example usage: [aio_post_category id="456" limit="3" separator=" | " class="custom-class" link="yes"]
 */

function aio_post_category_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,        // Default value is null
            'limit' => null,     // Default value is null (display all categories)
            'separator' => ', ', // Default separator is ", " (with space)
            'class' => '',       // Default value is an empty string for the class
            'link' => 'yes',    // Default value is 'yes' (category as link)
        ),
        $atts,
        'aio_post_category'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $post_id = get_the_ID();
    } else {
        // Use the specified post ID
        $post_id = absint($atts['id']);
    }

    $categories = get_the_category($post_id);

    if ($categories) {
        // Apply category limit
        if ($atts['limit']) {
            $categories = array_slice($categories, 0, $atts['limit']);
        }

        $category_list = array();
        foreach ($categories as $category) {
            if ($atts['link'] === 'yes') {
                $category_list[] = '<a class="' . esc_attr($atts['class']) . '" href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            } else {
                $category_list[] = '<span class="' . esc_attr($atts['class']) . '">' . esc_html($category->name) . '</span>';
            }
        }

        $separator = esc_html($atts['separator']);
        return implode($separator, $category_list);
    } else {
        return 'No categories found.';
    }
}
add_shortcode('aio_post_category', 'aio_post_category_shortcode');
