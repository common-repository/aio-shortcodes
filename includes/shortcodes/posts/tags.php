<?php
/**
 * Shortcode: [aio_post_tag]
 * Description: Displays the tags of the specified post.
 * Example usage: [aio_post_tag id="456" limit="3" separator=" | " class="custom-class" link="yes"]
 */

function aio_post_tag_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => null,        // Default value is null
            'limit' => null,     // Default value is null (display all tags)
            'separator' => ', ', // Default separator is ", " (with space)
            'class' => '',       // Default value is an empty string for the class
            'link' => 'yes',    // Default value is 'yes' (tag as link)
        ),
        $atts,
        'aio_post_tag'
    );

    if (empty($atts['id'])) {
        // If 'id' attribute is not provided, use the current post ID
        $post_id = get_the_ID();
    } else {
        // Use the specified post ID
        $post_id = absint($atts['id']);
    }

    $tags = get_the_tags($post_id);

    if ($tags) {
        // Apply tag limit
        if ($atts['limit']) {
            $tags = array_slice($tags, 0, $atts['limit']);
        }

        $tag_list = array();
        foreach ($tags as $tag) {
            if ($atts['link'] === 'yes') {
                $tag_list[] = '<a class="' . esc_attr($atts['class']) . '" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
            } else {
                $tag_list[] = '<span class="' . esc_attr($atts['class']) . '">' . esc_html($tag->name) . '</span>';
            }
        }

        $separator = esc_html($atts['separator']);
        return implode($separator, $tag_list);
    } else {
        return 'No tags found.';
    }
}
add_shortcode('aio_post_tag', 'aio_post_tag_shortcode');
