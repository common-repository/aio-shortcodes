<?php

// Shortcode Handler: aio_rank_math_faq

function aiosc_rank_math_faq_shortcode($atts, $content = null)
{
    // Define default attributes
    $atts = shortcode_atts(
        array(
            'list_style'    => 'none',      // Options: none, numbered, unordered
            'title_wrapper' => 'p',        // Options: h2, h3, h4, h5, h6, p, div
            'image_size'    => 'thumbnail', // Currently reserved for future use
            'class'         => '',          // Custom CSS class
        ),
        $atts,
        'aio_rank_math_faq'
    );

    // Extract attributes
    $list_style    = sanitize_text_field($atts['list_style']);
    $title_wrapper = sanitize_text_field($atts['title_wrapper']);
    $custom_class  = sanitize_html_class($atts['class']);

    // Validate title_wrapper
    $allowed_wrappers = array('h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div');
    if (! in_array(strtolower($title_wrapper), $allowed_wrappers, true)) {
        $title_wrapper = 'h2'; // Fallback to default
    }

    // Determine the list wrapper based on list_style
    switch (strtolower($list_style)) {
        case 'numbered':
            $list_tag_open  = '<ol class="rank-math-faq-list">';
            $list_tag_close = '</ol>';
            $use_list_items  = true;
            break;
        case 'unordered':
            $list_tag_open  = '<ul class="rank-math-faq-list">';
            $list_tag_close = '</ul>';
            $use_list_items  = true;
            break;
        case 'none':
        default:
            $list_tag_open  = '<div class="rank-math-faq-list">';
            $list_tag_close = '</div>';
            $use_list_items  = false;
            break;
    }

    // Split the content by line breaks to identify Q&A pairs
    // Ensure both Unix (\n) and Windows (\r\n) line endings are handled
    $content = do_shortcode($content);
    $faqs = preg_split('/\r\n|\n|\r/', trim($content));

    if (empty($faqs)) {
        return ''; // No content to display
    }
    $output     = $list_tag_open;
    $faq_schema = array();

    $current_question = '';
    $current_answer   = '';

    foreach ($faqs as $faq_line) {
        $faq_original = $faq_line;
        // Remove leading and trailing whitespace
        $faq_line = trim(strip_tags($faq_line));

        //echo substr(trim(strip_tags($faq_line)), -1);
        if (empty($faq_line)) {
            continue; // Skip empty lines
        }


        //var_dump(str_ends_with($faq_line, '?'));
        // Detect if the line ends with a '?'
        if (str_ends_with($faq_line, '?')) {
            // If there's an existing Q&A, process it
            if (! empty($current_question)) {
                // Generate HTML for the previous Q&A
                if ($use_list_items) {
                    $output .= '<li class="rank-math-faq-item">';
                } else {
                    $output .= '<div class="rank-math-faq-item">';
                }

                $output .= sprintf(
                    '<%1$s class="rank-math-question">%2$s</%1$s>',
                    esc_html($title_wrapper),
                    wp_kses_post($current_question)
                );
                $output .= '<div class="rank-math-answer"><p>' .  wp_kses_post($current_answer)  . '</p></div>';

                if ($use_list_items) {
                    $output .= '</li>';
                } else {
                    $output .= '</div>';
                }

                // Add to FAQ schema
                $faq_schema[] = array(
                    '@type'         => 'Question',
                    'name'          => wp_kses_post($current_question),
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => wp_kses_post($current_answer),
                    ),
                );
            }

            // Start a new Q&A pair
            $current_question = $faq_original;
            $current_answer   = '';
        } else {
            // Accumulate answer lines
            if (empty($current_question)) {
                // If there's no question yet, skip this line or handle as needed
                continue;
            }
            $current_answer .= $faq_original . ' ';
        }
    }

    // Handle the last Q&A pair if exists
    if (! empty($current_question)) {
        if ($use_list_items) {
            $output .= '<li class="rank-math-faq-item">';
        } else {
            $output .= '<div class="rank-math-faq-item">';
        }

        $output .= sprintf(
            '<%1$s class="rank-math-question">%2$s</%1$s>',
            esc_html($title_wrapper),
            wp_kses_post($current_question)
        );
        $output .= '<div class="rank-math-answer"><p>' .  wp_kses_post($current_answer)  . '</p></div>';

        if ($use_list_items) {
            $output .= '</li>';
        } else {
            $output .= '</div>';
        }

        // Add to FAQ schema
        $faq_schema[] = array(
            '@type'         => 'Question',
            'name'          => wp_kses_post($current_question),
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => wp_kses_post($current_answer),
            ),
        );
    }

    $output .= $list_tag_close;

    // Generate the FAQPage schema
    $schema_data = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'FAQPage',
        'mainEntity'  => $faq_schema,
    );

    // Encode schema in JSON-LD
    $schema_output = '<script type="application/ld+json">' . wp_json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

    // Final output with optional custom class
    $final_output = '<div class="aio-rank-math-faq ' . esc_attr($custom_class) . '">';
    $final_output .= $output;
    $final_output .= $schema_output;
    $final_output .= '</div>';

    return $final_output;
}

// Register the shortcode
add_shortcode('aio_rank_math_faq', 'aiosc_rank_math_faq_shortcode');
