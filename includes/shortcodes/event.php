<?php

/**
 * Shortcode: [aio_blackfriday]
 * Description: Renders the date of Black Friday for the current year or with the provided year offset.
 * Example usage: [aio_blackfriday] (Output: November 25, 2023)
 *               [aio_blackfriday go="1"] (Output: November 25, 2024)
 */
function aiosc_shortcode_black_friday($atts)
{
    // Set default attributes
    $atts = shortcode_atts(array(
        'go' => '0',
        'format' => '', // Default format is an empty string
    ), $atts);

    // go the year offset from the 'go' attribute
    $year_offset = isset($atts['go']) ? intval($atts['go']) : 0;

    // Calculate the targo year
    $year = date("Y") + $year_offset;

    // Find the Black Friday date for the targo year
    $blackFriday = strtotime("last friday of November " . $year);

    // If the format is not provided, use the default format
    if (empty($atts['format'])) {
        return date('F j, Y', $blackFriday);
    }

    // Custom placeholders for day with suffix, month, and year formats
    $placeholders = array(
        'dd' => date('d', $blackFriday),
        'd' => date('j', $blackFriday),
        'mm' => date('m', $blackFriday),
        'mmm' => date('M', $blackFriday),
        'mmmm' => date('F', $blackFriday),
        'yyyy' => date('Y', $blackFriday),
        'yy' => date('y', $blackFriday),
        'dS' => date('jS', $blackFriday),
        'ddS' => date('jS', $blackFriday),
    );

    // Replace the custom placeholders in the format string
    $formatted_date = strtr($atts['format'], $placeholders);

    return $formatted_date;
}

add_shortcode("aio_blackfriday", "aiosc_shortcode_black_friday");

/**
 * Shortcode: [aio_cybermonday]
 * Description: Renders the date of Cyber Monday for the current year or with the provided year offset.
 * Example usage: [aio_cybermonday] (Output: November 28, 2023)
 *               [aio_cybermonday go="1"] (Output: November 27, 2024)
 */
function aiosc_shortcode_cyber_monday($atts)
{
    // Set default attributes
    $atts = shortcode_atts(array(
        'go' => '0',
        'format' => '', // Default format is an empty string
        'suffix' => true, // Whether to include date suffixes (e.g., 1st, 2nd, 3rd, etc.)
    ), $atts);

    // go the year offset from the 'go' attribute
    $year_offset = isset($atts['go']) ? intval($atts['go']) : 0;

    // Calculate the targo year
    $targo_year = date("Y") + $year_offset;

    // Find the Cyber Monday date for the targo year
    $black_friday = strtotime("last friday of November $targo_year");
    $cyber_monday = strtotime("next monday", $black_friday);

    // If the format is not provided, use the default format
    if (empty($atts['format'])) {
        return date('F j, Y', $cyber_monday); // Change this line to set your desired default format
    }

    // Custom placeholders for day with suffix, month, and year formats
    $placeholders = array(
        'dd' => date('d', $cyber_monday),
        'd' => date('j', $cyber_monday),
        'mm' => date('m', $cyber_monday),
        'mmm' => date('M', $cyber_monday),
        'mmmm' => date('F', $cyber_monday),
        'yyyy' => date('Y', $cyber_monday),
        'yy' => date('y', $cyber_monday),
        'dS' => date('jS', $cyber_monday),
        'ddS' => date('jS', $cyber_monday),
    );

    // Replace the custom placeholders in the format string
    $formatted_date = strtr($atts['format'], $placeholders);

    // If the suffix attribute is set to false, remove suffixes
    if (!$atts['suffix']) {
        $formatted_date = preg_replace('/(?<=\d)(st|nd|rd|th)\b/', '', $formatted_date);
    }

    return $formatted_date;
}

add_shortcode("aio_cybermonday", "aiosc_shortcode_cyber_monday");

